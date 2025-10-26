#!/bin/bash
set -e

echo "Backup service starting..."
echo "Schedule: Daily at 02:00 UTC"

# Create S3 bucket if configured and doesn't exist
if [ -n "$AWS_ACCESS_KEY_ID" ] && [ -n "$S3_BUCKET" ]; then
    echo "Checking S3 bucket..."
    if ! aws s3 ls "s3://$S3_BUCKET" >/dev/null 2>&1; then
        echo "Creating S3 bucket: $S3_BUCKET"
        aws s3 mb "s3://$S3_BUCKET" --region "$AWS_DEFAULT_REGION" 2>&1 || echo "Bucket creation failed (might already exist)"
    else
        echo "S3 bucket exists: $S3_BUCKET"
    fi
fi

while true; do
    current_hour=$(date +%H)
    current_min=$(date +%M)
    
    if [ "$current_hour" = "02" ] && [ "$current_min" = "00" ]; then
        echo "Starting backup at $(date)"
        
        BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
        BACKUP_DIR="/backups/$BACKUP_DATE"
        mkdir -p "$BACKUP_DIR"
        
        # Database backup
        echo "Backing up database..."
        mariadb-dump -h db -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" | gzip > "$BACKUP_DIR/database.sql.gz"
        
        # Full backup on first run, incremental after
        if [ ! -f /backups/LAST_FULL ]; then
            echo "Creating full backup..."
            tar czf "$BACKUP_DIR/www-full.tar.gz" -C /www .
            tar czf "$BACKUP_DIR/config-full.tar.gz" -C /config .
            tar czf "$BACKUP_DIR/nginx-full.tar.gz" -C /nginx .
            tar czf "$BACKUP_DIR/ssl-full.tar.gz" -C /ssl .
            echo "$BACKUP_DATE" > /backups/LAST_FULL
        else
            echo "Creating incremental backup..."
            LAST_FULL=$(cat /backups/LAST_FULL)
            find /www -newer /backups/$LAST_FULL/www-full.tar.gz -type f 2>/dev/null | tar czf "$BACKUP_DIR/www-diff.tar.gz" -T - 2>/dev/null || touch "$BACKUP_DIR/www-diff.tar.gz"
            find /config -newer /backups/$LAST_FULL/config-full.tar.gz -type f 2>/dev/null | tar czf "$BACKUP_DIR/config-diff.tar.gz" -T - 2>/dev/null || touch "$BACKUP_DIR/config-diff.tar.gz"
            find /nginx -newer /backups/$LAST_FULL/nginx-full.tar.gz -type f 2>/dev/null | tar czf "$BACKUP_DIR/nginx-diff.tar.gz" -T - 2>/dev/null || touch "$BACKUP_DIR/nginx-diff.tar.gz"
            find /ssl -newer /backups/$LAST_FULL/ssl-full.tar.gz -type f 2>/dev/null | tar czf "$BACKUP_DIR/ssl-diff.tar.gz" -T - 2>/dev/null || touch "$BACKUP_DIR/ssl-diff.tar.gz"
        fi
        
        # Upload to S3
        if [ -n "$AWS_ACCESS_KEY_ID" ] && [ -n "$S3_BUCKET" ]; then
            echo "Uploading to S3..."
            aws s3 sync "$BACKUP_DIR" "s3://$S3_BUCKET/fenixlight/$BACKUP_DATE/" --storage-class STANDARD_IA
            echo "Backup uploaded to s3://$S3_BUCKET/fenixlight/$BACKUP_DATE/"
            
            # Cleanup old S3 backups (keep 30 days)
            CUTOFF_DATE=$(date -d '30 days ago' +%Y%m%d 2>/dev/null || date -v-30d +%Y%m%d)
            aws s3 ls "s3://$S3_BUCKET/fenixlight/" | awk '{print $2}' | while read prefix; do
                backup_date=$(echo "$prefix" | cut -d'_' -f1)
                if [ "$backup_date" \< "$CUTOFF_DATE" ]; then
                    echo "Removing old S3 backup: $prefix"
                    aws s3 rm "s3://$S3_BUCKET/fenixlight/$prefix" --recursive
                fi
            done
        else
            echo "S3 credentials not configured, backup saved locally only"
        fi
        
        # Cleanup old local backups (keep 7 days)
        find /backups -maxdepth 1 -type d -mtime +7 -exec rm -rf {} \; 2>/dev/null || true
        
        echo "Backup complete at $(date)"
        
        # Sleep for 2 minutes to avoid re-running
        sleep 120
    fi
    
    sleep 30
done

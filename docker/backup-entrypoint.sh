#!/bin/bash
set -e

echo "Backup service starting..."
echo "Schedule: Daily at 02:00 UTC"

# Authenticate with GCP using service account
if [ -n "$GOOGLE_APPLICATION_CREDENTIALS" ] && [ -f "$GOOGLE_APPLICATION_CREDENTIALS" ]; then
    echo "Authenticating with GCP..."
    gcloud auth activate-service-account --key-file="$GOOGLE_APPLICATION_CREDENTIALS"
    if [ $? -eq 0 ]; then
        echo "✅ GCP authentication successful"
    else
        echo "❌ GCP authentication failed"
        exit 1
    fi
else
    echo "❌ No GCP credentials found at $GOOGLE_APPLICATION_CREDENTIALS"
    exit 1
fi

# Create GCS bucket if configured and doesn't exist
if [ -n "$GCS_BUCKET" ]; then
    echo "Checking GCS bucket..."
    if ! gsutil ls "gs://$GCS_BUCKET" >/dev/null 2>&1; then
        echo "Creating GCS bucket: $GCS_BUCKET"
        gsutil mb -p "$GCP_PROJECT_ID" -c STANDARD -l "$GCP_REGION" "gs://$GCS_BUCKET" 2>&1 || echo "Bucket creation failed (might already exist)"
    else
        echo "GCS bucket exists: $GCS_BUCKET"
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
        
        # Upload to GCS
        if [ -n "$GCS_BUCKET" ]; then
            echo "Uploading to GCS..."
            gsutil -m rsync -r -d "$BACKUP_DIR" "gs://$GCS_BUCKET/fenixlight/$BACKUP_DATE/"
            echo "Backup uploaded to gs://$GCS_BUCKET/fenixlight/$BACKUP_DATE/"
            
            # Cleanup old GCS backups (keep 30 days)
            CUTOFF_DATE=$(date -d '30 days ago' +%Y%m%d 2>/dev/null || date -v-30d +%Y%m%d)
            gsutil ls "gs://$GCS_BUCKET/fenixlight/" | grep -E '/[0-9]{8}_[0-9]{6}/$' | while read backup_path; do
                backup_date=$(basename "$backup_path" | cut -d'_' -f1)
                if [ "$backup_date" \< "$CUTOFF_DATE" ]; then
                    echo "Removing old GCS backup: $backup_path"
                    gsutil -m rm -r "$backup_path"
                fi
            done
        else
            echo "GCS credentials not configured, backup saved locally only"
        fi
        
        # Cleanup old local backups (keep 7 days)
        find /backups -maxdepth 1 -type d -mtime +7 -exec rm -rf {} \; 2>/dev/null || true
        
        echo "Backup complete at $(date)"
        
        # Sleep for 2 minutes to avoid re-running
        sleep 120
    fi
    
    sleep 30
done

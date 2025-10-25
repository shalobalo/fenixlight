<?php

class shopSetPluginCartModel extends waModel
{
	protected $table = 'shop_set_cart';
	
	public function addSetToCart($set_id,$code)
	{
		$id = 0;
		$data = array(
			'code' => $code,
			'set_id' => $set_id
		);
		if ( $r = $this->getByField($data) )
		{
			$id = $r['id'];
			$this->updateById($id,array('count'=>$r['count']+1));
		}
		else
			$id = $this->insert(array(
				'set_id' => $set_id,
				'code' => $code,
				'count' => 1,
			));
		
		return $id;
	}
	
	
	public function changeSetQuantity($set_id,$code,$quantity=0,$ignore=false)
	{
		if ( !empty($code) && $set_id > 0 )
		{
			if ( $quantity > 0 )
			{
				$max = $this->getMaxAvailableSetCount($code,$set_id);
				if ( $max !== true )
					$quantity = ( $max < $quantity ) ? $max : $quantity;
				
				//$set_cart_item_model = new shopSetPluginCartItemModel;
				
				if ( !$ignore )
				{
					$cart_item_model = new shopCartItemsModel;
					if ( $rows = $this->_itemsQuery($code,$set_id) )
						foreach ( $rows as $row )
						{
							$this->updateById($row['id'],array('count',$quantity));
							$qty = $row['quantity'] + $row['item_count']*($quantity - $row['cart_count']);
							$cart_item_model->updateById($row['cart_item_id'],array('quantity'=>$qty));
						}
					
					if ( $row = $this->_mainItemQuery($code,$set_id) )
					{
						$qty = $row['quantity'] + $quantity - $row['count'];
						$cart_item_model->updateById($row['id'],array('quantity'=>$qty));
					}
				}
				
				$this->query('UPDATE '.$this->table.' SET `count`='.(int)$quantity.' WHERE set_id='.(int)$set_id." AND code LIKE '".$this->escape($code)."'");
			}
			else
				$this->remove($code,$set_id,$ignore);
		}
		return $quantity;
	}
	
	
	public function getMaxAvailableSetCount($code,$set_id)
	{
		$max = false;
		if ( !empty($code) && $set_id > 0 )
		{
			$q = "
				SELECT
				  MIN(SubQuery.max) AS min
				FROM (SELECT
					ROUND( (ps.count-ci.quantity) / si.count) AS max
				  FROM shop_cart_items ci
					INNER JOIN shop_product_skus ps
					  ON ci.sku_id = ps.id
					INNER JOIN shop_set_item si
					  ON si.sku_id = ci.sku_id
				  WHERE ci.code LIKE '".$this->escape($code)."'
				  AND si.set_id = ".(int)$set_id."
				  AND ps.count IS NOT NULL) SubQuery
			";
			$max = $this->query($q)->fetchField('min');
			
			$q = "
				SELECT
				  p.count - c.quantity AS max
				FROM shop_set_set s
				  INNER JOIN shop_product_skus p
					ON s.sku_id = p.id
				  INNER JOIN shop_cart_items c
					ON s.sku_id = c.sku_id
				WHERE c.code LIKE '".$this->escape($code)."'
				AND s.id = ".(int)$set_id."
				AND p.count IS NOT NULL
			";
			$product_max = $this->query($q)->fetchField('max');
			
			if ( $max )
			{
				if ( $product_max )
					$max = ( $product_max < $max ) ? $product_max : $max;
			}
			else
			{
				if ( $product_max )
					$max = $product_max;
				else
					$max = true;
			}
			
		}
		return $max;
	}
	
	
	public function getCartSets($code)
	{
		$sets = false;
		if ( !empty($code) )
		{
			$q = "
			SELECT
			  ci.id,
			  sc.count * si.count AS count,
			  si.set_id,
			  si.sort,
			  si.price
			FROM shop_set_cart_item sci
			  RIGHT OUTER JOIN shop_set_cart sc
				ON sc.id = sci.set_cart_id
			  RIGHT OUTER JOIN shop_set_item si
				ON sc.set_id = si.set_id
			  LEFT OUTER JOIN shop_cart_items ci
				ON ci.id = sci.cart_item_id
				AND si.sku_id = ci.sku_id
			WHERE ci.code LIKE '".$this->escape($code)."'
			ORDER BY si.set_id, si.sort
			";
			
			if ( $r = $this->query($q)->fetchAll() )
				foreach ( $r as $v )
				{
					$s = $v['sort'];
					do $s++; while ( isset($sets[$v['set_id']][$s]) );
					$sets[$v['set_id']][$s] = array(
						'item_id' => $v['id'],
						'count' => $v['count'],
						'price' => $v['price'],
					);
				}
			
			$q = "
				SELECT
				  c.id,
				  sq.count,
				  sq.set_id
				FROM (SELECT
					s.sku_id,
					c.set_id,
					c.count
				  FROM shop_set_cart c
					INNER JOIN shop_set_set s
					  ON c.set_id = s.id
				  WHERE c.code LIKE '".$this->escape($code)."') sq
				  INNER JOIN shop_cart_items c
					ON sq.sku_id = c.sku_id
				WHERE c.code LIKE '".$this->escape($code)."'
			";
			if ( $r = $this->query($q)->fetchAll() )
				foreach ( $r as $v )
					if ( isset($sets[$v['set_id']]) )
					{
						$sets[$v['set_id']][0] = array(
							'item_id' => $v['id'],
							'count' => $v['count']
						);
						ksort($sets[$v['set_id']]);
					}
		}
		return $sets;
	}
	
	
	public function remove($code,$set_id,$ignore=false)
	{
		$items = $this->_itemsQuery($code,$set_id);
		$main = $this->_mainItemQuery($code,$set_id);
		
		$cart_item_model = new shopCartItemsModel;
		$set_cart_item_model = new shopSetPluginCartItemModel;
		
		$id = 0;
		if ( $items )
		{
			if ( $ignore )
				$id = $items[0]['id'];
			else
				foreach ( $items as $i )
				{
					$qty = $i['quantity'] - $i['item_count']*$i['cart_count'];
					if ( $qty > 0 )
						$cart_item_model->updateById($i['cart_item_id'],array('quantity'=>$qty));
					else
						$cart_item_model->deleteById($i['cart_item_id']);
					
					if ( $id == 0 )
						$id = $i['id'];
				}
		}
		$this->deleteById($id);
		$set_cart_item_model->deleteByField('set_cart_id',$id);
		
		if ( $main && !$ignore )
		{
			$qty = $main['quantity'] - $main['count'];
			if ( $qty > 0 )
				$cart_item_model->updateById($main['id'],array('quantity'=>$qty));
			else
				$cart_item_model->deleteById($main['id']);
		}
	}
	
	
	private function _itemsQuery($code,$set_id)
	{
		$q = "
			SELECT
			  c.count AS cart_count,
			  k.count AS item_count,
			  s.quantity,
			  i.cart_item_id,
			  c.id
			FROM shop_set_cart c
			  RIGHT OUTER JOIN shop_set_item k
				ON c.set_id = k.set_id
			  RIGHT OUTER JOIN shop_set_cart_item i
				ON i.set_cart_id = c.id
			  RIGHT OUTER JOIN shop_cart_items s
				ON i.cart_item_id = s.id
			WHERE c.code LIKE '".$this->escape($code)."'
			AND s.sku_id = k.sku_id
			AND c.set_id = ".(int)$set_id."
		";
		
		return $this->query($q)->fetchAll();
	}
	
	
	
	private function _mainItemQuery($code,$set_id)
	{
		$q = "
			SELECT
			  i.id,
			  i.quantity,
			  c.count
			FROM shop_set_set s
			  RIGHT OUTER JOIN shop_set_cart c
				ON s.id = c.set_id
			  INNER JOIN shop_set_cart_item r
				ON r.set_cart_id = c.id
			  INNER JOIN shop_cart_items i
				ON r.cart_item_id = i.id
			WHERE c.set_id = ".(int)$set_id."
			AND c.code LIKE '".$this->escape($code)."'
			AND c.code = i.code
			AND s.sku_id = i.sku_id
		";
		return $this->query($q)->fetch();
	}

	public function getSetCartItems($code)
	{
		$q = "
			SELECT
			  i.cart_item_id as id
			FROM shop_set_cart_item i
			  LEFT OUTER JOIN shop_set_cart c
				ON i.set_cart_id = c.id
			WHERE c.code LIKE '".$this->escape($code)."'
		";
		$ids = array();
		if ( $rows = $this->query($q)->fetchAll('id') )
			$ids = array_keys($rows);
		return $ids;
	}
}
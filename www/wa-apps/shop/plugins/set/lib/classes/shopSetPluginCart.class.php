<?php

class shopSetPluginCart extends shopCart
{
	public function getCartSets()
	{
		$model = new shopSetPluginCartModel;
		return $model->getCartSets($this->getCode());
	}
	
	
	public function getCartSetCounts()
	{
		$model = new shopSetPluginCartModel;
		return $model->getByField('code',$this->getCode(),true);
	}
	
	
	public function getSetCartItems()
	{
		$sets = $this->getCartSets();

		if ( !empty($sets) )
		{
			$items = $this->items();
			
			$product_ids = $sku_ids = array();
			foreach ($items as $item)
			{
				$product_ids[] = $item['product_id'];
				$sku_ids[] = $item['sku_id'];
			}

			$product_ids = array_unique($product_ids);
			$sku_ids = array_unique($sku_ids);
			
			$product_model = new shopProductModel();
			if (waRequest::param('url_type') == 2)
				$products = $product_model->getWithCategoryUrl($product_ids);
			else
				$products = $product_model->getById($product_ids);
			
			$sku_model = new shopProductSkusModel();
			$skus = $sku_model->getByField('id', $sku_ids, 'id');
			
			$image_model = new shopProductImagesModel();

			foreach ($items as $item_id => &$item) {
				if ( !isset($skus[$item['sku_id']]) )
				{
					unset($items[$item_id]);
					continue;
				}
				if ($item['type'] == 'product')
				{
					$item['product'] = $products[$item['product_id']];
					$sku = $skus[$item['sku_id']];
					if ($sku['image_id'] && $sku['image_id'] != $item['product']['image_id']) {
						$img = $image_model->getById($sku['image_id']);
						if ($img) {
							$item['product']['image_id'] = $sku['image_id'];
							$item['product']['ext'] = $img['ext'];
						}
					}
					$item['sku_name'] = $sku['name'];
					$item['sku_code'] = $sku['sku'];
					$item['price'] = $sku['price'];
					$item['compare_price'] = $sku['compare_price'];
					$item['currency'] = $item['product']['currency'];
					$type_ids[] = $item['product']['type_id'];
					if (isset($errors[$item_id])) {
						$item['error'] = $errors[$item_id];
						if (strpos($item['error'], '%s') !== false) {
							$item['error'] = sprintf($item['error'], $item['product']['name'].($item['sku_name'] ? ' ('.$item['sku_name'].')' : ''));
						}
					}
				}
			}
			unset($item);
			
								

			
			foreach ( $sets as $set_id => &$set )
			{
				$s = $set;
				$set = array();
				$set['items'] = $s;
				$set['count'] = $s[0]['count'];
				$set['total'] = 0;
				$set['set_item_total'] = 0;
				foreach ( $set['items'] as $k=>&$v )
					if ( isset($items[$v['item_id']]) )
					{
						$item = $items[$v['item_id']];
						$c = $v['count'];
						$item['set_item_count'] = $c;
						
						if ( isset($v['price']) )
							$item['price'] = $v['price'];
							
							
							
						$item['full_price'] = shop_currency(ceil($item['price']) * $c, $item['currency'], null, false);
						$set['total'] += $item['full_price'];
						$set['set_item_total'] += shop_currency(ceil($item['price']) * $c /*/ $set['count']*/, $item['currency'], null, false);
						$v = $item;
					}
					else
						unset($sets[$set_id][$k]);
			}
		}
		
		return $sets;
	}
	
	public function getSingleItems()
	{
		$items = array();
		$sets = $this->getCartSets();
		$cart_items = $this->items();
		$counts = array();
		if ( !empty($sets) && !empty($cart_items) )
			foreach ( $sets as $set_id=>$set )
				foreach ( $set as $k=>$v )
				{
					$id = $v['item_id'];
					$i = $cart_items[$id];
					if ( isset($items[$id]) )
						$c = $items[$id]['quantity'] - $v['count'];
					else
						$c = $i['quantity'] - $v['count'];
					
					if ( $c < 0 )
					{
						$set_cart_model = new shopSetPluginCartModel;
						$set_cart_model->changeSetQuantity($set_id,$this->getCode(),0,true);
						$c += $v['count'];
					}
					
					$items[$id] = array(
						'quantity' => $c,
						'total' => shop_currency($i['price']*$c, $i['currency'], null, true)
					);
				}
		
		return $items;
	}
	
	public function order($order_id)
	{
		$cart_sets = $this->getCartSetCounts();
		if ( $cart_sets )
		{
			$cart_item_model = new shopSetPluginCartItemModel;
			$cart_model = new shopSetPluginCartModel;
			$ordered_set_model = new shopSetPluginOrderedSetModel;
			foreach ( $cart_sets as $v )
			{
				$id = $v['id'];
				$cart_item_model->deleteByField('set_cart_id', $id);
				$cart_model->deleteById($id);
				$ordered_set_model->insert(array(
					'set_id' => $v['set_id'],
					'order_id' => $order_id,
					'count' => $v['count'],
				));
			}
		}
	}
	
	// todo $id=>$set_id
	public function getItemsWithoutSet()
	{
		$items = $this->items();
		$ids = array_keys($items);
		$set_ids = array();
		if ( !empty($ids) )
		{
			$cart_model = new shopSetPluginCartModel;
			$set_item_ids = $cart_model->getSetCartItems($this->getCode());
			if ( !empty($set_item_ids) )
				$ids = array_diff($ids,$set_item_ids);
			
			if ( !empty($ids) )
			{
				$set_model = new shopSetPluginSetModel;
				$set_item_model = new shopSetPluginItemModel;
				foreach ( $ids as $id )
				{
					$sku_id = $items[$id]['sku_id'];
					if ( $rows = $set_model->getByField('sku_id',$sku_id,true) )
						foreach ( $rows as $row )
							$set_ids[$id][$row['id']] = $row['id'];
					if ( $rows = $set_item_model->getByField('sku_id',$sku_id,true) )
						foreach ( $rows as $row )
							$set_ids[$id][$row['set_id']] = $row['set_id'];
				}
			}
		}
		
		return $set_ids;
	}
}
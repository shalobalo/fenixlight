<?php

class shopSetPluginCartChecker
{
	// проверка на совпадение количества товаров из комплекта в корзине
	public function check()
	{
		$cart = new shopCart;
		$code = $cart->getCode();
		$set_cart_model = new shopSetPluginCartModel;
		if ( $cart_sets = $set_cart_model->getByField('code',$code,true) )
		{
			$set_cart_item_model = new shopSetPluginCartItemModel;
			$set_item_model = new shopSetPluginItemModel;
			$set_model = new shopSetPluginSetModel;
			$items = $cart->items();
			foreach ( $cart_sets as $cart_set )
			{
				$cart_set_count = $cart_set['count'];
				$set_cart_id = $cart_set['id'];
				$set_id = $cart_set['set_id'];
				
				$counts = array();
				if ( $rows = $set_item_model->getByField('set_id',$set_id,true) )
				{
					foreach ( $rows as $row )
						$counts[$row['sku_id']] = $row['count']*$cart_set_count;
					$result = $set_model->getById($set_id);
					$counts[$result['sku_id']] = 1;
				}

				if ( !empty($counts) && $set_cart_items = $set_cart_item_model->getByField('set_cart_id',$set_cart_id,true) )
				{
					foreach ( $set_cart_items as $set_cart_item )
					{
						$item_id = $set_cart_item['cart_item_id'];
						$qty = 0;
						if ( isset($items[$item_id]) )
						{
							$item = $items[$item_id];
							$qty = $item['quantity'];
							if ( $qty < $counts[$item['sku_id']] )
								$qty = 0;
						}
						if ( $qty == 0 )
						{
							$set_cart_model->deleteById($set_cart_id);
							$set_cart_item_model->deleteByField('set_cart_id',$set_cart_id);
						}
					}
				}
			}
		}
	}
}
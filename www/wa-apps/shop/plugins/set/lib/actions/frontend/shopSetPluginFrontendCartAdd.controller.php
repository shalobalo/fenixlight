<?php

class shopSetPluginFrontendCartAddController extends waJsonController
{
	public function execute()
	{
	
		$cart_model = new shopCartItemsModel();
		$code = waRequest::cookie('shop_cart');
		if (!$code) {
			$code = md5(uniqid(time(), true));
			wa()->getResponse()->addHeader('P3P', 'CP="NOI ADM DEV COM NAV OUR STP"');
			wa()->getResponse()->setCookie('shop_cart', $code, time() + 30 * 86400, null, '', false, true);
		}
		
		$set_id = waRequest::post('set_id', 0);
		$sku_id = waRequest::post('sku_id', 0);
		
		if ( $sku_id == 0 && $set_id > 0 )
		{
			$set_model = new shopSetPluginSetModel;
			if ( $row = $set_model->getById($set_id) )
				$sku_id = $row['sku_id'];
		}
				
		
		$result['available'] = 0;
		if ( $sku_id > 0 && $set_id > 0 )
		{
			$sku_model = new shopProductSkusModel();
			$sku = $sku_model->getById($sku_id);						
			
			
			$product_id = $sku['product_id'];

			$m = new shopSetPluginSetModel;
			$product_sets = $m->getSetsByProductId($product_id);
			
			$set = null;
			if ( isset($product_sets[$sku_id]['sets'][$set_id]) )
				$set = $product_sets[$sku_id]['sets'][$set_id];
			
			if ( !empty($set) && $set['available'] && !empty($set['items']) )
			{
				$set_cart_model = new shopSetPluginCartModel;
				$set_cart_item_model = new shopSetPluginCartItemModel;
				
				$set_cart_id = $set_cart_model->addSetToCart($set_id,$code);
				$product_item = array(
					'product_id' => $product_id,
					'sku_id' => $sku_id,
					'count' => 1,
				);
				foreach ( $set['items']+array($product_item) as $i )
				{
					$item_id = null;
					$item = $cart_model->getSingleItem($code, $i['product_id'], $i['sku_id']);
					if ($item) {
						$item_id = $item['id'];
						$cart_model->updateById($item_id, array('quantity' => $item['quantity'] + $i['count']));
					}
					if (!$item_id) {
						$data = array(
							'code' => $code,
							'contact_id' => $this->getUser()->getId(),
							'product_id' => $i['product_id'],
							'sku_id' => $i['sku_id'],
							'create_datetime' => date('Y-m-d H:i:s'),
							'quantity' => $i['count'],
						);
						$item_id = $cart_model->insert($data + array('type' => 'product'));
					}
					
					/* recalc new price */
										
					if ( isset($i['sku_price'])) {
						$price = $i['sku_price'] - ( $i['sku_price'] * $i['percent'] / 100 );
						$m->updatePrice($price, $i['id']);
					}
					
					$set_cart_item_model->addItem($set_cart_id,$item_id);
				}
				
				$set_cart_item_model->clearItems($code);
				
				$product_sets = $m->getSetsByProductId($product_id);
								
				$set = $product_sets[$sku_id]['sets'][$set_id];
				$result['available'] = $set['available'];
			}
		}
		
		$shop_cart = new shopCart($code);
		wa()->getStorage()->remove('shop/cart');
		$result['total'] = $shop_cart->total();
		$result['total_with_currency'] = shop_currency($shop_cart->total());
		$result['total_html'] = shop_currency_html($shop_cart->total());
		$result['count'] = $shop_cart->count();
		
		if (waRequest::isXMLHttpRequest())
			$this->response = $result;
		else
			$this->redirect(waRequest::server('HTTP_REFERER'));

	}
}
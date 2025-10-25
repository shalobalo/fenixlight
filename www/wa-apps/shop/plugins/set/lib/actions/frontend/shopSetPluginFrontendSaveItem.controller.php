<?php

class shopSetPluginFrontendSaveItemController extends waJsonController
{
	public function execute()
	{
		$item_id = waRequest::post('item_id', 0);
		$qty = waRequest::post('qty', 0);
		
		if ( $qty >= 0 )
		{
			$set_cart = new shopSetPluginCart;
			$single_items = $set_cart->getSingleItems();
			
			$d = $qty - $single_items[$item_id]['quantity'];
			$cart_items_model = new shopCartItemsModel();
			$item = $cart_items_model->getById($item_id);
			$q = $item['quantity'] + $d;
			
			$this->response['error'] = 0;
			if ( !wa()->getSetting('ignore_stock_count') && $item['type'] == 'product' )
			{
				$product_model = new shopProductModel();
				$p = $product_model->getById($item['product_id']);
				$sku_model = new shopProductSkusModel();
				$sku = $sku_model->getById($item['sku_id']);
				if ( $sku['count'] !== null && $q > $sku['count'] )
				{
					$q = $sku['count'];
					$this->response = array(
						'error' => 1,
						'name' => $p['name'].($sku['name'] ? ' ('.$sku['name'].')' : '')
					);
				}
			}
			
			$cart = new shopCart();
			$cart->setQuantity($item_id, $q);
			$single_items = $set_cart->getSingleItems();
			
			$this->response['item_total'] = $single_items[$item_id]['total'];
			$this->response['q'] = $single_items[$item_id]['quantity'];
			
			$total = $cart->total();
			$discount = $cart->discount();
			
			$this->response['total'] = shop_currency_html($total, true);
			$this->response['discount'] = shop_currency_html($discount, true);
			$this->response['discount_numeric'] = $discount;
			$this->response['count'] = $cart->count();
		}
	}
}
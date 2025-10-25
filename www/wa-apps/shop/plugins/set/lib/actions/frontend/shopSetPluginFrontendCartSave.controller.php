<?php

class shopSetPluginFrontendCartSaveController extends waJsonController
{
	public function execute()
	{
		$code = waRequest::cookie('shop_cart');
		
		$set_id = waRequest::post('set_id',0,'int_type');
		$quantity = waRequest::post('quantity',0,'int_type');
		
		if ( $set_id > 0 )
		{
			$model = new shopSetPluginCartModel;
			$quantity = $model->changeSetQuantity($set_id,$code,$quantity);
		}
		
		wa()->getStorage()->remove('shop/cart');
		$cart = new shopCart();
		$total = $cart->total();
		$discount = $cart->discount();
		
		$m = new shopSetPluginSetModel;
		$this->response = array(
			'quantity' => $quantity,
			'item_total' => shop_currency_html($quantity*$m->getSetTotal($set_id),true),
			'total' => shop_currency_html($total, true),
			'discount' => shop_currency_html($discount, true),
			'count' => $cart->count()
		);
	}
}
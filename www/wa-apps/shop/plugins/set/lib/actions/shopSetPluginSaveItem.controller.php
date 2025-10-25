<?php

class shopSetPluginSaveItemController extends waJsonController
{

	public function execute()
	{
		$item_id = waRequest::post('item_id',0,'type_int');
		$price = waRequest::post('price',0,'type_float');
		$percent = waRequest::post('percent',0,'type_int');
		$sku_id = waRequest::post('sku_id',0,'type_int');
		$count = waRequest::post('count',1,'type_int');
		$currency = waRequest::post('currency',1,'string_trim');
		
		$price = $price - ( $price * $percent / 100);
		
		if ( $item_id > 0 )
		{
			$data = array(
				'price' => $price,
				'count' => $count,
				'percent' => $percent
			);
			if ( $sku_id )
				$data['sku_id'] = $sku_id;
			$model = new shopSetPluginItemModel;
			$model->updateById($item_id,$data);
		}				
		
		$this->response = array(
			'price' => shop_currency($price,$currency,$currency)
		);
	}

}
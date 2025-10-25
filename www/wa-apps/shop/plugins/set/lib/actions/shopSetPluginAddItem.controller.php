<?php

class shopSetPluginAddItemController extends waJsonController
{

	public function execute()
	{
		$set_id = waRequest::post('set_id',0,'type_int');
		$product_id = waRequest::post('product_id',0,'type_int');
		$sku_id = waRequest::post('sku_id',0,'type_int');
		$sort = waRequest::post('sort',0,'type_int');
		$item_id = 0;
		$data = 0;
		if ( $set_id && $sku_id && $product_id )
		{
			$model = new shopProductModel;
			$product = $model->getById($product_id);
			
			$model = new shopProductSkusModel;
			$sku = $model->getById($sku_id);
			
			$model = new shopSetPluginItemModel;
			$count = $model->countByField(array('set_id'=>$set_id,'sku_id'=>$sku_id));
			if ( !$count )
				$item_id = $model->insert(array(
					'set_id' => $set_id,
					'sku_id' => $sku_id,
					'price' => $sku['price'],
					'currency' => $product['currency'],
					'sort' => $sort,
				));
			$image_url = shopImage::getUrl(array(
				'product_id' => $product['id'],
				'id' => $product['image_id'],
				'ext' => $product['ext']
			), 80);
			$item_image = ( !empty($image_url) ) ? '<img src="'.$image_url.'">' : '';
			if ( $item_id && !empty($product) && !empty($sku) )
				$data = array(
					'item_id' => $item_id,
					'item_name' => $product['name'],
					'item_sku_name' => $sku['name'],
					'item_sku' => $sku['sku'],
					'item_sku_id' => $sku_id,
					'item_price' => shop_currency($sku['price'],$product['currency'],$product['currency'],false),
					'item_currency' => $product['currency'],
					'item_image' => $item_image,
				);
		}
		
		$this->response = $data ? $data : 0;
	}

}
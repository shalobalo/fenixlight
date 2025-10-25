<?php

class shopSetPluginEditItemController extends waJsonController
{

	public function execute()
	{
		$item_id = waRequest::post('item_id',0,'type_int');
		$skus = array();
		$price = 0;
		if ( $item_id > 0 )
		{
			$model = new shopSetPluginItemModel;
			if ( $item = $model->getById($item_id) )
			{
				$sku_id = $item['sku_id'];
				$price = $item['price'];
				$percent = $item['percent'];
				$count = $item['count'];
				
				$skus_model = new shopProductSkusModel;
				if ( $sku = $skus_model->getById($sku_id) )
				{
					$product_id = $sku['product_id'];
					$skus = $skus_model->select('id, image_id, name, sku, price')
										->where('product_id = '.(int)$product_id)
										->fetchAll('id');
				}
			}
			
			$this->response = array(
				'price' => $price,
				'skus' => $skus,
				'sku_id' => $sku_id,
				'sku_count' => count($skus),
				'count' => $count,
				'percent' => $percent
			);
		}
	}

}
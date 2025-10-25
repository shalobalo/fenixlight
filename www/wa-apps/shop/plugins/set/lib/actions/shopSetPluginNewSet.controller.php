<?php

class shopSetPluginNewSetController extends waJsonController
{

	public function execute()
	{
		$sku_id = waRequest::post('sku_id',0,'type_int');
		$set_id = 0;
		if ( $sku_id )
		{
			$model = new shopSetPluginSetModel;
			$set_id = $model->insert(array('sku_id'=>$sku_id));
		}
		$this->response = $set_id ? $set_id : 0;
	}

}
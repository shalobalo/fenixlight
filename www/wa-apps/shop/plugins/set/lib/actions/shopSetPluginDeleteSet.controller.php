<?php

class shopSetPluginDeleteSetController extends waJsonController
{

	public function execute()
	{
		$set_id = waRequest::post('set_id',0,'type_int');
		if ( $set_id )
		{
			$model = new shopSetPluginSetModel;
			$model->deleteById($set_id);
			$model = new shopSetPluginItemModel;
			$model->deleteByField('set_id',$set_id);
		}
	}

}
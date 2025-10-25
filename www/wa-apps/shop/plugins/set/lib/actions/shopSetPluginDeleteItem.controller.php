<?php

class shopSetPluginDeleteItemController extends waJsonController
{

	public function execute()
	{
		$item_id = waRequest::post('item_id',0,'type_int');
		if ( $item_id > 0 )
		{
			$model = new shopSetPluginItemModel;
			$model->deleteById($item_id);
		}
	}

}
<?php

class shopSetPluginSaveSortController extends waJsonController
{

	public function execute()
	{
		$item_ids = waRequest::post('items_ids',array(),'type_array_int');

		if ( !empty($item_ids) )
		{
			$model = new shopSetPluginItemModel;
			foreach ( $item_ids as $k=>$id )
				$model->updateById($id,array('sort'=>$k));
		}
	}

}
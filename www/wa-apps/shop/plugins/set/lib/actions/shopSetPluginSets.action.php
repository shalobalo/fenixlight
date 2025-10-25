<?php

class shopSetPluginSetsAction extends waViewAction
{

	public function execute()
	{
		$id = waRequest::get('id',0,'type_int');
		$m = new shopSetPluginSetModel;
		$product_sets = $m->getSetsByProductId($id);
		
		$this->view->assign('product_sets',$product_sets);
	}

}
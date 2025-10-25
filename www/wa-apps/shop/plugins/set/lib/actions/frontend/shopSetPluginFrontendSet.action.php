<?php

class shopSetPluginFrontendSetAction extends waViewAction
{

	public function execute()
	{
	
		$set_id = waRequest::post('set_id',0,'type_int');
		$m = new shopSetPluginSetModel;
		$items = $m->getSetItemsById($set_id);
		$main = $m->getSetMainItemById($set_id);
		
		$f = new shopSetPluginFiles;
		
		$m = new shopSetPluginCart;
		$cart_items = $m->getSetCartItems();
		$cart_item = isset($cart_items[$set_id]) ? $cart_items[$set_id] : false;
		
		$view = wa()->getView();
		$view->assign('items',$items);
		$view->assign('main',$main);
		$view->assign('set_id',$set_id);
		$view->assign('cart_item',$cart_item);
		$html = $view->fetch('string:'.$f->getFileContent('set'));
		
		$this->view->assign('html',$html);
	}

}
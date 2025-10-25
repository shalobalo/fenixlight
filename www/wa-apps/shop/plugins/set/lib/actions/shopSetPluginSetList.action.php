<?php

class shopSetPluginSetListAction extends waViewAction
{

	public function execute()
	{
		$model = new shopSetPluginSetModel;
		//$list = $model->getSetList();
		$this->view->assign('list',$model->getSetList());
	}

}
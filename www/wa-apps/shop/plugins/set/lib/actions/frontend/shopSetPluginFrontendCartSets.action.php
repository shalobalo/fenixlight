<?php

class shopSetPluginFrontendCartSetsAction extends waViewAction
{

	public function execute()
	{
		$view = wa()->getView();
		$this->view->assign('html',wa('shop')->getPlugin('set')->frontendCart());
	}

}
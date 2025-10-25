<?php

class shopSetPluginSaveFileController extends waJsonController
{

	public function execute()
	{
		$theme = waRequest::post('theme','');
		$f = new shopSetPluginFiles($theme);
		$f->saveFromPostData();
	}

}
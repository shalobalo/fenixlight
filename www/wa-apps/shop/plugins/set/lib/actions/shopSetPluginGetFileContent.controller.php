<?php

class shopSetPluginGetFileContentController extends waJsonController
{

	public function execute()
	{
		$theme = waRequest::post('theme','');
		$name = waRequest::post('name','');
		
		$f = new shopSetPluginFiles($theme);
		$this->response = $f->getFileContent($name);
	}

}
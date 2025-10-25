<?php

class shopSetPluginSettingsAction extends waViewAction
{
	public function execute()
	{
		$plugin_id = 'set';
		$plugin = wa()->getPlugin($plugin_id);
		$path = 'plugins/'.$plugin_id.'/';
		
		$response = $this->getResponse();
		$response->addJs($path.'js/jquery.waapplugindisign.js','shop');
		$response->addCss($path.'css/jquery.waapplugindisign.css','shop');
		$response->addJs($path.'js/jquery.waapplugindesc.js','shop');
		$response->addCss($path.'css/jquery.waapplugindesc.css','shop');
		$this->view->assign('js',$response->getJs(true,true));
		$this->view->assign('css',$response->getCss(true,true));
		
		$standart_settings = $plugin->getControls(array(
			'subject' => 'standart',
			'namespace' => 'shop_'.$plugin_id,
			'title_wrapper' => '%s',
			'description_wrapper' => '<br><span class="hint">%s</span>',
			'control_wrapper' => '<div class="field"><div class="name">%s</div><div class="value">%s%s</div></div>',
		));
		
		$selector_settings = $plugin->getControls(array(
			'subject' => 'selector',
			'namespace' => 'shop_'.$plugin_id,
			'title_wrapper' => '%s',
			'description_wrapper' => '<br><span class="hint">%s</span>',
			'control_wrapper' => '<div class="field"><div class="name">%s</div><div class="value">%s%s</div></div>',
		));
		
		$f = new shopSetPluginFiles;
		$this->view->assign(array(
			'themes' => $f->getThemes(),
			'settings' => $plugin->getSettings(),
			'standart_settings' => implode('',$standart_settings),
			'selector_settings' => implode('',$selector_settings),
		));
	}

}
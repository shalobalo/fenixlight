<?php

class shopSetPlugin extends shopPlugin
{
	// event: backend_products
	public function backendProducts()
	{
		return array(
			'sidebar_top_li' => '<li id="s-action-sets">
			<a href="#/actionsets/"><i class="icon16" style="background-image: url(\''.$this->getPluginStaticUrl().'img/set.png\');"></i>Акционные комплекты</a>
			<script src="'.$this->getPluginStaticUrl().'js/products.js?v'.$this->info['version'].'"></script>
			</li>'
		);
	}
	
	
	// event: backend_product
	public function backendProduct($product)
	{
		$html = '';
		
		if ( $this->getSettings('on') )
		{
			$view = wa()->getView();
			$html = $view->fetch($this->path.'/templates/toolbar.html');
		}
		return array(
			'toolbar_section' => $html,
			'edit_section_li' => '<li class="sets"><a href="#/product/'.$product['id'].'/edit/sets">Комплекты</a></li>',
		);
	}
	
	// event: frontend_product
	public function frontendProduct($product)
	{
		$html = '';
		if ( $this->getSettings('on') && $this->getSettings('pon') )
			$html = self::_setHTML($product);
		return array(
			'block'=> $html
		);
	}
	
	
	// event: order_action.create
	public function orderActionCreate($data)
	{
		$cart = new shopSetPluginCart;
		$cart->order($data['order_id']);
	}
	
	
	static public function set($product)
	{
		$html = '';
		$plugin = wa()->getPlugin('set');
		if ( $plugin->getSettings('on') )
			$html = self::_setHTML($product);
		return $html;
	}
	
	
	static protected function _setHTML($product)
	{
		$id = ( $product instanceof shopProduct || isset($product['id']) ) ? $product['id'] : $product;
		$html = '';
		if ( is_numeric($id) && $id > 0 )
		{
			$view = wa()->getView();
			$view->assign('product_id',$id);
			
			$m = new shopSetPluginSetModel;
			$product_sets = $m->getSetsByProductId($id);
			
			if ( !empty($product_sets) )
				foreach ( $product_sets as $sku_id=>$v )
					if ( empty($v['sets']) )
						unset($product_sets[$sku_id]);
			
			if ( !empty($product_sets) )
			{
				$view->assign('product_sets',$product_sets);
				$f = new shopSetPluginFiles;
				$html = $view->fetch('string:'.$f->getFileContent('complect'));
			}
		}
		return $html;
	}
	
	
	// event: frontend_cart
	public function frontendCart()
	{
		$html = '';
		if ( $this->getSettings('on') )
		{
			$cart = new shopSetPluginCart;
			$view = wa()->getView();
			$view->assign(array(
				'single_items' => json_encode($cart->getSingleItems()),
				'cart_sets_items' => $cart->getSetCartItems(),
				'items_without_set' => $cart->getItemsWithoutSet(),
			));
			$f = new shopSetPluginFiles;
			$html = $view->fetch('string:'.$f->getFileContent('cart'));
		}
		return $html;
	}
	
	
	// event: cart_delete
	public function cartDelete()
	{
		if ( $this->getSettings('on') )
		{
			$cart = new shopSetPluginCart;
			$cart->getSingleItems();
		}
	}
	
	
	// event: frontend_head
	public function frontendHead()
	{
		$html = '';		

		if ( $this->getSettings('on') )
		{
			if ( $this->getSettings('arcticmodal') )
			{
				$response = waSystem::getInstance()->getResponse();
				$aurl = 'plugins/set/js/arcticmodal/';
				$response->addCss($aurl.'jquery.arcticmodal-0.3.css','shop');
				$response->addCss($aurl.'themes/simple.css','shop');
				$response->addJs($aurl.'jquery.arcticmodal-0.3.min.js','shop');
			}
			
			$f = new shopSetPluginFiles;
			$f->addCss('css');
			$f->addJs('js');
			$view = wa()->getView();
			$view->assign('settings', $this->getSettings());
			$html = $view->fetch('string:'.$f->getFileContent('head'));
		}               
        
		return $html;
	}
	
	// event: order_calculate_discount
	public function orderCalculateDiscount($params)
	{
		$d = 0;
		if ( $this->getSettings('on') )
		{
			$checker = new shopSetPluginCartChecker;
			$checker->check();
			
			$cart = new shopSetPluginCart;
			$counts = $cart->getCartSetCounts();
			if ( !empty($counts) )
			{
				$model = new shopSetPluginSetModel;
				foreach ( $counts as $c )
					$d += $model->getDiscount($c['set_id'])*$c['count'];
			}
		}
		return $d;
	}
	
	// event: backend_order
	public function backendOrder($data)
	{
		$html = '';
		if ( $this->getSettings('on') )
		{
			$order_id = $data['id'];
			$model = new shopSetPluginOrderedSetModel;
			$view = wa()->getView();
			$view->assign('set_ids',$model->getSetIds($order_id));
			$html = $view->fetch($this->path.'/templates/aux_info.html');
		}
		return array(
			'aux_info' => $html
		);
	}
}
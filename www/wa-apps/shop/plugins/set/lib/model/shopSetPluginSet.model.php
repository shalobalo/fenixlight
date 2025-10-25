<?php

class shopSetPluginSetModel extends waModel
{
	protected $_item_model;
	protected $table = 'shop_set_set';
	protected $_image_fields = array(
		'image_id' => 0,
		'ext' => '',
		'image_desc' => '',
	);
	protected $_sets = array();
	protected $_cart_model;
	protected $_cart_code = false;
	protected $_product_urls = array();
	
	public function __construct($type = null, $writable = false)
	{
		parent::__construct($type, $writable);
		$this->_item_model = new shopSetPluginItemModel;
		
		if ( wa()->getEnv() == 'frontend' )
		{
			$this->_cart_model = new shopCartItemsModel();
			$this->_cart_code = waRequest::cookie('shop_cart');
		}
	}
	
	// определение sku_ids для данного товара. Для каждого sku_id определяется комплект
	public function getSetsByProductId($id)
	{
		$sets = array();
		$this->_set_sku_ids = array();
		if ( $id )
		{
			$product = new shopProduct($id);
			$skus = $product->skus;
			if ( !empty($skus) )
				foreach ( $skus as $sku_id=>$sku )
					$sets[$sku_id] = $this->getSets($sku_id);
		}
		
		return $this->_addProductInfoToSets($sets);
	}
	
	
	public function getSets($sku_id)
	{
		if ( !isset($this->_sets[$sku_id]) )
		{
			$sets = array();
			if ( $sku_id && $r = $this->getByField('sku_id',$sku_id,true) )
				foreach ( $r as $k=>$set )
				{
					$sets[$set['id']] = $set;
					$sets[$set['id']]['items'] = $this->_item_model->where('set_id = '.(int)$set['id'])
															->order('sort')
															->fetchAll('id');
				}
			$this->_sets[$sku_id] = $sets;
		}
		return $this->_sets[$sku_id];
	}
	
	
	protected function _addProductInfoToSets($product_sets)
	{
		$products = $this->_getProductsData($product_sets);
		if ( !empty($product_sets) && !empty($products) )
			foreach ( $product_sets as $sku_id=>$sets )
			{
				$product_sets[$sku_id] = $this->_separateImageData($products[$sku_id]);
				$product_price = shop_currency($products[$sku_id]['sku_price'], $products[$sku_id]['currency'], null, false);
				$product_sets[$sku_id]['available'] = $this->_isAvailable($sku_id,$products[$sku_id]['sku_count'],1);
				
				$product_sets[$sku_id]['url'] = $this->_getFrontendProductUrl($products[$sku_id]['product_id']);
				if ( !empty($sets) )
					foreach ( $sets as $k=>$set )
					{
						$total_set_price = $product_price;
						//echo $product_price;exit;
						$sets[$k]['available'] = 1;
						if ( !empty($set['items']) )
							foreach ( $set['items'] as $item_id=>$item )
							{
								
								$p = $this->_separateImageData($products[$item['sku_id']]);
								$item = array_merge($item,$p);
								$sku_price = shop_currency($item['sku_price'], $item['currency'], null, false);
								
								$price = shop_currency($sku_price-($sku_price*$item['percent']/100), $item['currency'], null, false);
								$total_set_price += $item['count']*ceil($price);
																
								$item['discount'] = $this->_culculateDiscount($sku_price,$price,$item['id']);
								$item['available'] = $this->_isAvailable($item['sku_id'],$item['sku_count'],$item['count']);
								if ( !$item['available'] )
									$sets[$k]['available'] = 0;
								$item['url'] = $this->_getFrontendProductUrl($item['product_id']);
								$sets[$k]['items'][$item_id] = $item;
							}
						$sets[$k]['total'] = ceil(0.01*round(100*$total_set_price));
						//print_r($sets);	
					}
				$product_sets[$sku_id]['sets'] = $sets;
			}
		
		return $product_sets;
	}
	
	
	protected function _getProductsData($product_sets)
	{
		$products = array();
		if ( !empty($product_sets) )
		{
			$sku_ids = $this->_getProductSetsSkuIds($product_sets);
			if ( !empty($sku_ids) )
			{
				$in = implode(',',$sku_ids);
				$q = "
				SELECT DISTINCT
					  p.name,
					  s.id AS sku_id,
					  s.price AS sku_price,
					  s.sku,
					  s.count as sku_count,
					  s.name AS sku_name,
					  p.currency,
					  p.id AS product_id,
					  i.ext AS sku_ext,
					  s.image_id AS sku_image_id,
					  i.description AS sku_image_desc,
					  pi.ext AS product_ext,
					  pi.description AS product_image_desc,
					  p.image_id AS product_image_id
					FROM shop_product_skus s
					  RIGHT OUTER JOIN shop_product p
						ON s.product_id = p.id
					  LEFT OUTER JOIN shop_product_images i
						ON s.image_id = i.id
					  LEFT OUTER JOIN shop_product_images pi
						ON p.image_id = pi.id
					WHERE s.id IN ($in)
				";
				$products = $this->query($q)->fetchAll('sku_id');
				$this->_initFrontendProductUrl($products);
			}
		}
		return $products;
	}
	
	
	protected function _initFrontendProductUrl($products)
	{
		if ( wa()->getEnv() == 'frontend' )
		{
			$k_ids = array();
			if ( !empty($products) )
				foreach ( $products as $p )
					$k_ids[$p['product_id']] = 1;
			if ( !empty($k_ids) )
			{
				$ids = array_keys($k_ids);
				$collection = new shopProductsCollection($ids);
				$collection_products = $collection->getProducts('*');
				foreach ( $collection_products as $p )
					$this->_product_urls[$p['id']] = $p['frontend_url'];
			}
		}
	}
	
	
	protected function _getFrontendProductUrl($product_id)
	{
		return ( isset($this->_product_urls[$product_id]) ) ? $this->_product_urls[$product_id] : '';
	}
	
	
	protected function _getProductSetsSkuIds($product_sets)
	{
		$sku_ids_k = array();
		if ( !empty($product_sets) )
			foreach ( $product_sets as $sku_id=>$sets )
			{
				$sku_ids_k[$sku_id] = 1;
				if ( !empty($sets) )
					foreach ( $sets as $set )
						if ( !empty($set['items']) )
							foreach ( $set['items'] as $item )
								$sku_ids_k[(int)$item['sku_id']] = 1;
			}
		
		return ( !empty($sku_ids_k) ) ? array_keys($sku_ids_k) : array();
	}
	
	
	protected function _isAvailable($sku_id,$sku_count,$count)
	{
		$available = 1;
		if ( wa()->getEnv() == 'frontend' && !wa()->getSetting('ignore_stock_count') )
		{
			$c = $this->_cart_model->countSku($this->_cart_code, $sku_id);
			if ( $sku_count !== null && $c + $count > $sku_count )
				$available = 0;
		}
		return $available;
	}
	
	
	protected function _culculateDiscount($price,$set_price,$item_id)
	{
		$discount = 0;
		if ( $price > 0 )
		{
			if ( $price > $set_price )
				$discount = round(100*($price - $set_price)/$price);
			else
				$this->_item_model->updateById($item_id,array('price'=>$price));
		}
		return $discount;
	}
	
	public function updatePrice($price, $item_id) {
		$this->_item_model->updateById($item_id,array('price'=>ceil($price)));
	}
	
	protected function _separateImageData($data)
	{
		$image = array();
		
		foreach ( array('sku_','product_') as $prefix )
			if ( isset($data[$prefix.'image_id']) )
				foreach ( $this->_image_fields as $field=>$default )
					$image[$field] = ( isset($data[$prefix.$field]) ) ? $data[$prefix.$field] : $default;

		foreach ( array('sku_','product_') as $prefix )
			foreach ( $this->_image_fields as $field=>$default )
				unset($data[$prefix.$field]);
		
		if ( !empty($image) )
			$image['id'] = $data['product_id'];
		$data['image'] = $image;
		return $data;
	}
	
	
	public function getSetTotal($set_id)
	{
		$total = 0;
		if ( $set_id > 0 )
		{
			$q = "
				SELECT
				  p.price,
				  shop_product.currency
				FROM shop_set_set s
				  INNER JOIN shop_product_skus p
					ON s.sku_id = p.id
				  INNER JOIN shop_product
					ON p.product_id = shop_product.id
				WHERE s.id = ".(int)$set_id."
			";
			if ( $r = $this->query($q) )
				if ( $item = $r->fetch() )
					$total = shop_currency($item['price'], $item['currency'], null, false);
			
			if ( $rows = $this->_item_model->getByField('set_id',$set_id,true) )
				foreach ( $rows as $item )
					$total += $item['count']*shop_currency($item['price'], $item['currency'], null, false);
		}
		return $total;
	}
	
	
	public function getDiscount($set_id)
	{
		$d = 0;
		if ( $set_id > 0 )
		{
			$q = "
				SELECT
				  (s.price - i.price)*i.count AS d,
				  p.currency
				FROM shop_set_item i
				  INNER JOIN shop_product_skus s
					ON i.sku_id = s.id
				  INNER JOIN shop_product p
					ON s.product_id = p.id
				WHERE i.set_id = ".(int)$set_id."
			";
			if ( $r = $this->query($q)->fetchAll() )
				foreach ( $r as $row )
					$d += shop_currency($row['d'], $row['currency'], null, false);
		}
		return $d;
	}
	
	
	public function getSetItemsById($set_id)
	{
		$items = array();
		$q = "
			SELECT
			  si.set_id,
			  si.price AS set_price,
			  si.currency,
			  si.sku_id,
			  si.count,
			  ps.price,
			  p.name,
			  p.ext,
			  ps.product_id,
			  ps.image_id AS sku_image_id,
			  p.image_id,
			  ps.sku,
			  ps.name AS sku_name,
			  p.url
			FROM shop_set_set ss
			  RIGHT JOIN shop_set_item si
				ON ss.id = si.set_id
			  RIGHT JOIN shop_product_skus ps
				ON si.sku_id = ps.id
			  RIGHT JOIN shop_product p
				ON ps.product_id = p.id
			WHERE ss.id = ".(int)$set_id;
		if ( $r = $this->query($q)->fetchAll() )
			foreach ( $r as $k=>$row )
			{
				$row['image'] = array(
					'image_id' => ( $row['sku_image_id'] ) ? $row['sku_image_id'] : $row['image_id'],
					'image_desc' => '',
					'id' => $row['product_id'],
					'ext' => $row['ext'],
				);
				unset($row['sku_image_id']);
				unset($row['image_id']);
				unset($row['ext']);
				$items[$k] = $row;
				$d = shop_currency($row['price']-$row['set_price'], $row['currency'], null, false);
				$items[$k]['discount'] = ceil(100*$d/shop_currency($row['price'], $row['currency'], null, false));
			}
		return $items;
	}
	
	public function getSetMainItemById($set_id)
	{
		$q = "
			SELECT
			  ps.price,
			  p.name,
			  p.ext,
			  ps.product_id,
			  ps.image_id AS sku_image_id,
			  p.image_id,
			  p.currency,
			  ps.sku,
			  ps.name AS sku_name,
			  p.url
			FROM shop_product_skus ps
			  RIGHT OUTER JOIN shop_product p
				ON ps.product_id = p.id
			  LEFT OUTER JOIN shop_set_set ss
				ON ss.sku_id = ps.id
			WHERE ss.id = ".(int)$set_id;
		if ( $row = $this->query($q)->fetch() )
		{
			$row['image'] = array(
				'image_id' => ( $row['sku_image_id'] ) ? $row['sku_image_id'] : $row['image_id'],
				'image_desc' => '',
				'id' => $row['product_id'],
				'ext' => $row['ext'],
			);
			unset($row['sku_image_id']);
			unset($row['image_id']);
			unset($row['ext']);
		}
		return $row;
	}
	
	
	function getSetList()
	{
		$q = "
		SELECT DISTINCT
		  k.sku,
		  k.name AS sku_name,
		  p.name,
		  p.id
		FROM shop_set_set s
		  INNER JOIN shop_product_skus k
			ON s.sku_id = k.id
		  INNER JOIN shop_product p
			ON k.product_id = p.id
		";
		return $this->query($q)->fetchAll();
	}
}
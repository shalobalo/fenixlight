<?php

class shopSetPluginOrderedSetModel extends waModel
{
	protected $table = 'shop_set_ordered_set';
	
	public function getSetIds($order_id)
	{
		$q = "
			SELECT
			  k.product_id,
			  o.set_id,
			  o.count
			FROM shop_set_ordered_set o
			  INNER JOIN shop_set_set s
				ON o.set_id = s.id
			  INNER JOIN shop_product_skus k
				ON s.sku_id = k.id
			WHERE o.order_id = ".(int)$order_id;
		return $this->query($q)->fetchAll();
	}
}
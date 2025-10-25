<?php

class shopSetPluginCartItemModel extends waModel
{
	protected $table = 'shop_set_cart_item';
	
	public function addItem($set_cart_id,$cart_item_id)
	{
		$r = $this->getByField(array(
			'set_cart_id' => $set_cart_id,
			'cart_item_id' => $cart_item_id
		));
		if ( !$r )
		{
			$this->insert(array(
				'set_cart_id' => $set_cart_id,
				'cart_item_id' => $cart_item_id
			));
		}
	}
	
	
	public function clearItems($code)
	{
		$q = "
			SELECT
			  s.id
			FROM shop_set_cart c
			  RIGHT OUTER JOIN shop_set_cart_item s
				ON c.id = s.set_cart_id
			  LEFT OUTER JOIN shop_cart_items i
				ON s.cart_item_id = i.id
			WHERE c.code LIKE '".$this->escape($code)."'
			AND i.id IS NULL
		";
		if ( $r = $this->query($q)->fetchAll('id') )
		{
			$in = implode(',',array_keys($r));
			$this->query("DELETE FROM {$this->table} WHERE id IN ($in)");
		}
	}
}
<?php

return array(
	'shop_set_set' => array(
		'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
		'sku_id' => array('int', 11, 'null' => 0),
		'price' => array('decimal', "15,4", 'null' => 0, 'default' => 0),
		':keys' => array(
			'PRIMARY' => 'id',
			'sku_id' => 'sku_id',
		),
	),
	'shop_set_item' => array(
		'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
		'set_id' => array('int', 11, 'null' => 0),
		'sku_id' => array('int', 11, 'null' => 0),
		'count' => array('int', 11, 'null' => 0, 'default' => '1'),
		'price' => array('decimal', "15,4", 'null' => 0, 'default' => 0),
		'currency' => array('char', 3),
		'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
		':keys' => array(
			'PRIMARY' => 'id',
			'sku_id' => array('set_id', 'sku_id', 'unique' => 1),
		),
	),
	'shop_set_cart' => array(
		'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
		'set_id' => array('int', 11, 'null' => 0),
		'count' => array('int', 11, 'null' => 0),
		'code' => array('varchar', 32),
		':keys' => array(
			'PRIMARY' => 'id',
			'set_id' => array('set_id', 'code', 'unique' => 1),
		),
	),
	'shop_set_cart_item' => array(
		'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
		'set_cart_id' => array('int', 11, 'null' => 0),
		'cart_item_id' => array('int', 11, 'null' => 0),
		':keys' => array(
			'PRIMARY' => 'id',
			'cart' => array('set_cart_id', 'cart_item_id', 'unique' => 1),
		),
	),
	'shop_set_ordered_set' => array(
		'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
		'set_id' => array('int', 11, 'null' => 0),
		'order_id' => array('int', 11, 'null' => 0),
		'count' => array('int', 11, 'null' => 0),
		':keys' => array(
			'PRIMARY' => 'id',
			'order' => array('set_id', 'order_id', 'unique' => 1),
		),
	),
);
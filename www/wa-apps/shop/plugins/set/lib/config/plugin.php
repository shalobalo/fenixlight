<?php

return array(
	'name' => 'Акционные комплекты',
	'description' => '',
	'vendor' => '929600',
	'version' => '1.0.800',
	'img' => 'img/set.png',
	'shop_settings' => true,
	'frontend' => true,
	'handlers' => array(
		'backend_products' => 'backendProducts',
		'backend_product' => 'backendProduct',
		'frontend_product' => 'frontendProduct',
		'frontend_cart' => 'frontendCart',
		'frontend_head' => 'frontendHead',
		'order_calculate_discount' => 'orderCalculateDiscount',
		'order_action.create' => 'orderActionCreate',
		'backend_order' => 'backendOrder',
		'cart_delete' => 'cartDelete',
	),
);
//EOF
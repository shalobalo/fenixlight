<?php

return array(
	'on' => array(
		'title' => 'Включить плагин',
		'description' => '',
		'value' => true,
		'control_type' => waHtmlControl::CHECKBOX,
		'subject' => 'standart',
	),
	'pon' => array(
		'title' => 'Включить вывод комплекта',
		'description' => 'Включить вывод блока комплектов через хук frontend_product на странице товара',
		'value' => true,
		'control_type' => waHtmlControl::CHECKBOX,
		'subject' => 'standart',
	),
	'arcticmodal' => array(
		'title' => 'Включить arcticmodal',
		'description' => 'Отключите arcticmodal (плагин модального окна), если он уже подключен в теме или в других плагинах',
		'value' => true,
		'control_type' => waHtmlControl::CHECKBOX,
		'subject' => 'standart',
	),
	'cart_form_selector' => array(
		'title' => 'Форма корзины',
		'description' => 'jQuery-селектор тега <form> корзины. В селекторе используйте двойные кавычки вместо одинарных.',
		'value' => '.main-content form',
		'control_type' => waHtmlControl::INPUT,
		'class' => 'long',
		'subject' => 'selector',
	),
	'cart_item_selector' => array(
		'title' => 'Позиция корзины',
		'description' => 'jQuery-селектор DOM-элемента корзины, где должна размещаться информация о комплектах для данного товара. Ключевое слово ITEMID в селекторе будет заменено на значение идентификатора позиции в корзине (каждый товар, помещенный в корзину имеет такой идентификатор). В селекторе используйте двойные кавычки вместо одинарных.',
		'value' => '.cart .row[data-id="ITEMID"] .item-name',
		'control_type' => waHtmlControl::INPUT,
		'class' => 'long',
		'subject' => 'selector',
	),
	'cart_item_qty_selector' => array(
		'title' => 'Количество позиции',
		'description' => 'jQuery-селектор поля количества элемента в корзине. Ключевое слово ITEMID в селекторе будет заменено на значение идентификатора позиции в корзине (каждый товар, помещенный в корзину имеет такой идентификатор). В селекторе используйте двойные кавычки вместо одинарных.',
		'value' => '.cart .row[data-id="ITEMID"] .item-qty .qty',
		'control_type' => waHtmlControl::INPUT,
		'class' => 'long',
		'subject' => 'selector',
	),
);
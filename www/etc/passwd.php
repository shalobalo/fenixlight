<?php
return array (
  'brands' => 
  array (
    'name' => 'Brands',
    'description' => 'Storefront’s product filtering by brand (manufacturer)',
    'vendor' => 'webasyst',
    'version' => '1.1',
    'img' => 'wa-apps/shop/plugins/brands/img/brands.png',
    'shop_settings' => true,
    'frontend' => true,
    'icons' => 
    array (
      16 => 'img/brands.png',
    ),
    'handlers' => 
    array (
      'frontend_nav' => 'frontendNav',
      'sitemap' => 'sitemap',
      'routing' => 'routing',
    ),
    'id' => 'brands',
    'app_id' => 'shop',
  ),
  'yandexmarket' => 
  array (
    'name' => 'Яндекс.Маркет',
    'description' => 'Экспорт каталога товаров в формате YML',
    'img' => 'wa-apps/shop/plugins/yandexmarket/img/yandexmarket.png',
    'vendor' => 'webasyst',
    'version' => '1.2.2',
    'importexport' => 'profiles',
    'export_profile' => true,
    'frontend' => true,
    'handlers' => 
    array (
      'backend_products' => 'backendProductsEvent',
      'routing' => 'routing',
    ),
    'id' => 'yandexmarket',
    'app_id' => 'shop',
  ),
  'migrate' => 
  array (
    'name' => 'Migrate to Shop-Script',
    'description' => 'Transfer data from other ecommerce platforms to Shop-Script',
    'img' => 'wa-apps/shop/plugins/migrate/img/migrate.png',
    'vendor' => 'webasyst',
    'version' => '1.1.0',
    'importexport' => true,
    'handlers' => 
    array (
      'backend_welcome' => 'backendWelcomeHandler',
    ),
    'id' => 'migrate',
    'app_id' => 'shop',
  ),
  'cml1c' => 
  array (
    'name' => '1С',
    'description' => 'Обмен данными с «1С: Управление торговлей» (CommerceML)',
    'img' => 'wa-apps/shop/plugins/cml1c/img/1c.png',
    'vendor' => 'webasyst',
    'version' => '2.0.0',
    'importexport' => true,
    'frontend' => true,
    'handlers' => 
    array (
      'products_collection' => 'productsCollection',
      'routing' => 'routing',
    ),
    'locale' => 
    array (
      0 => 'ru_RU',
    ),
    'id' => 'cml1c',
    'app_id' => 'shop',
  ),
  'watermark' => 
  array (
    'name' => 'Watermark',
    'description' => 'Applies watermark text or image on uploaded photos',
    'img' => 'wa-apps/shop/plugins/watermark/img/watermark.png',
    'vendor' => 'webasyst',
    'version' => '1.0.0',
    'rights' => false,
    'handlers' => 
    array (
      'image_upload' => 'imageUpload',
    ),
    'id' => 'watermark',
    'app_id' => 'shop',
  ),
  'custommenu' => 
  array (
    'name' => 'Custommenu',
    'description' => 'Storefront’s product filtering by brand (manufacturer)',
    'vendor' => 'webasyst',
    'version' => '992482',
    'img' => 'wa-apps/shop/plugins/custommenu/img/brands.png',
    'frontend' => true,
    'icons' => 
    array (
      16 => 'img/brands.png',
    ),
    'handlers' => 
    array (
      'backend_menu' => 'addBackendSettings',
      'custom_menu' => 'displayMenu',
      'routing' => 'routing',
    ),
    'id' => 'custommenu',
    'app_id' => 'shop',
  ),
  'custom' => 
  array (
    'name' => 'Плагин кастомизации Shop Script 5',
    'description' => 'Плагин кастомизации Shop Script 5 для fonarik',
    'vendor' => '992482',
    'version' => '1.0.0',
    'img' => 'wa-apps/shop/plugins/custom/',
    'shop_settings' => true,
    'frontend' => true,
    'icons' => 
    array (
      16 => '',
    ),
    'handlers' => 
    array (
      'backend_menu' => 'addBackendSettingsScript',
      'backend_product' => 'addCategoriesForProduct',
      'backend_products' => 'addCopyProductsButton',
      'routing' => 'routing',
    ),
    'id' => 'custom',
    'app_id' => 'shop',
  ),
  'set' => 
  array (
    'name' => 'Акционные комплекты',
    'description' => '',
    'vendor' => '929600',
    'version' => '1.0.800',
    'img' => 'wa-apps/shop/plugins/set/img/set.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => 
    array (
      'backend_products' => 'backendProducts',
      'backend_product' => 'backendProduct',
      'frontend_product' => 'frontendProduct',
      'frontend_cart' => 'frontendCart',
      'frontend_head' => 'frontendHead',
      'order_calculate_discount' => 'orderCalculateDiscount',
      'order_action.create' => 'orderActionCreate',
      'backend_order' => 'backendOrder',
      'cart_delete' => 'cartDelete',
      'routing' => 'routing',
    ),
    'id' => 'set',
    'app_id' => 'shop',
  ),
);

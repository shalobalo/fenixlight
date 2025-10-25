<?php
return array (
  'contactinfo' => 
  array (
    'name' => 'Контактная информация',
    'fields' => 
    array (
      'phone' => 
      array (
        'localized_names' => 'Телефон',
        'required' => '1',
      ),
      'email' => 
      array (
        'localized_names' => 'Email',
        'required' => '',
      ),
      'firstname' => 
      array (
        'localized_names' => 'Имя',
        'required' => '',
      ),
      'lastname' => 
      array (
        'localized_names' => 'Фамилия',
        'required' => '',
      ),
      'sposob-oplaty' => 
      array (
        'required' => '1',
      ),
      'address' => 
      array (
        'localized_names' => 'Адрес',
        'fields' => 
        array (
          'city' => 
          array (
            'localized_names' => 'Город, улица, дом, квартира, индекс',
            'required' => '1',
          ),
        ),
      ),
      'address.shipping' => 
      array (
        'localized_names' => 'Адрес доставки',
        'fields' => 
        array (
          'city' => 
          array (
            'localized_names' => 'Город, улица, дом, квартира, индекс',
            'required' => '1',
          ),
        ),
      ),
    ),
  ),
);

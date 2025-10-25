<?php
return array (
  0 => 
  waContactStringField::__set_state(array(
     'id' => 'socialnetwork',
     'options' => 
    array (
      'multi' => true,
      'type' => 'SocialNetwork',
      'ext' => 
      array (
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'linkedin' => 'LinkedIn',
        'vkontakte' => 'VKontakte',
      ),
      'formats' => 
      array (
        'top' => 
        waContactSocialNetworkTopFormatter::__set_state(array(
           '_type' => 'waContactSocialNetworkTopFormatter',
           'options' => NULL,
        )),
        'js' => 
        waContactSocialNetworkJSFormatter::__set_state(array(
           '_type' => 'waContactSocialNetworkJSFormatter',
           'options' => NULL,
        )),
      ),
      'domain' => 
      array (
        'facebook' => 'facebook.com',
        'vkontakte' => 'vk.com',
        'twitter' => 'twitter.com',
        'linkedin' => NULL,
      ),
      'storage' => 'data',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 0 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'multi' => true,
          'type' => 'SocialNetwork',
          'ext' => 
          array (
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
            'vkontakte' => 'VKontakte',
          ),
          'formats' => 
          array (
            'top' => 
            waContactSocialNetworkTopFormatter::__set_state(array(
               '_type' => 'waContactSocialNetworkTopFormatter',
               'options' => NULL,
            )),
            'js' => 
            waContactSocialNetworkJSFormatter::__set_state(array(
               '_type' => 'waContactSocialNetworkJSFormatter',
               'options' => NULL,
            )),
          ),
          'domain' => 
          array (
            'facebook' => 'facebook.com',
            'vkontakte' => 'vk.com',
            'twitter' => 'twitter.com',
            'linkedin' => NULL,
          ),
          'storage' => 'data',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Social network',
    ),
     '_type' => 'waContactStringField',
  )),
  1 => 
  waContactBirthdayField::__set_state(array(
     'id' => 'birthday',
     'options' => 
    array (
      'storage' => 'info',
      'prefix' => 'birth',
      'formats' => 
      array (
        'html' => 
        waContactBirthdayLocalFormatter::__set_state(array(
           '_type' => 'waContactBirthdayLocalFormatter',
           'options' => 
          array (
            'prefix' => 'birth',
          ),
        )),
        'locale' => 
        waContactBirthdayLocalFormatter::__set_state(array(
           '_type' => 'waContactBirthdayLocalFormatter',
           'options' => 
          array (
            'prefix' => 'birth',
          ),
        )),
        'list' => 
        waContactBirthdayLocalFormatter::__set_state(array(
           '_type' => 'waContactBirthdayLocalFormatter',
           'options' => 
          array (
            'prefix' => 'birth',
          ),
        )),
      ),
      'validators' => 
      waDateValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'This is a required field',
          'invalid' => 'Invalid',
          'incorrect_date' => 'Направильная дата',
        ),
         'options' => 
        array (
          'required' => false,
          'storage' => 'info',
          'prefix' => 'birth',
          'formats' => 
          array (
            'html' => 
            waContactBirthdayLocalFormatter::__set_state(array(
               '_type' => 'waContactBirthdayLocalFormatter',
               'options' => 
              array (
                'prefix' => 'birth',
              ),
            )),
            'locale' => 
            waContactBirthdayLocalFormatter::__set_state(array(
               '_type' => 'waContactBirthdayLocalFormatter',
               'options' => 
              array (
                'prefix' => 'birth',
              ),
            )),
            'list' => 
            waContactBirthdayLocalFormatter::__set_state(array(
               '_type' => 'waContactBirthdayLocalFormatter',
               'options' => 
              array (
                'prefix' => 'birth',
              ),
            )),
          ),
          'validators' => 
          array (
          ),
          'allow_self_edit' => false,
          'unique' => false,
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waDateValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Birthday',
    ),
     '_type' => 'waContactBirthdayField',
  )),
  2 => 
  waContactEmailField::__set_state(array(
     'id' => 'email',
     'options' => 
    array (
      'multi' => true,
      'storage' => 'email',
      'ext' => 
      array (
        'work' => 'work',
        'personal' => 'personal',
      ),
      'top' => true,
      'validators' => 
      waEmailValidator::__set_state(array(
         'options' => 
        array (
          'required' => false,
          'pattern' => '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--(?:--)?)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD',
          'multi' => true,
          'storage' => 'email',
          'ext' => 
          array (
            'work' => 'work',
            'personal' => 'personal',
          ),
          'top' => true,
        ),
         'messages' => 
        array (
          'required' => 'This is a required field',
          'invalid' => 'Invalid',
          'not_match' => 'Email-адрес введен неправильно',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waEmailValidator',
      )),
      'formats' => 
      array (
        'js' => 
        waContactEmailListFormatter::__set_state(array(
           '_type' => 'waContactEmailListFormatter',
           'options' => NULL,
        )),
        'top' => 
        waContactEmailTopFormatter::__set_state(array(
           '_type' => 'waContactEmailTopFormatter',
           'options' => NULL,
        )),
        'html' => 
        waContactEmailTopFormatter::__set_state(array(
           '_type' => 'waContactEmailTopFormatter',
           'options' => NULL,
        )),
      ),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Email',
    ),
     '_type' => 'waContactEmailField',
  )),
  3 => 
  waContactPhoneField::__set_state(array(
     'id' => 'phone',
     'options' => 
    array (
      'multi' => true,
      'ext' => 
      array (
        'work' => 'work',
        'mobile' => 'mobile',
        'home' => 'home',
      ),
      'top' => true,
      'storage' => 'data',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 0 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'multi' => true,
          'ext' => 
          array (
            'work' => 'work',
            'mobile' => 'mobile',
            'home' => 'home',
          ),
          'top' => true,
          'storage' => 'data',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'formats' => 
      array (
        'js' => 
        waContactPhoneJsFormatter::__set_state(array(
           '_type' => 'waContactPhoneJsFormatter',
           'options' => NULL,
        )),
        'value' => 
        waContactPhoneFormatter::__set_state(array(
           '_type' => 'waContactPhoneFormatter',
           'options' => NULL,
        )),
        'html' => 
        waContactPhoneTopFormatter::__set_state(array(
           '_type' => 'waContactPhoneTopFormatter',
           'options' => NULL,
        )),
        'top' => 
        waContactPhoneTopFormatter::__set_state(array(
           '_type' => 'waContactPhoneTopFormatter',
           'options' => NULL,
        )),
      ),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Phone',
    ),
     '_type' => 'waContactPhoneField',
  )),
  4 => 
  waContactAddressField::__set_state(array(
     'id' => 'address',
     'options' => 
    array (
      'multi' => true,
      'ext' => 
      array (
        'work' => 'work',
        'home' => 'home',
        'shipping' => 'shipping',
        'billing' => 'billing',
      ),
      'storage' => 'data',
      'fields' => 
      array (
        'city' => 
        waContactStringField::__set_state(array(
           'id' => 'city',
           'options' => 
          array (
            'storage' => 'data',
            'validators' => 
            waStringValidator::__set_state(array(
               'messages' => 
              array (
                'required' => 'Required',
                'invalid' => 'Invalid',
                'max_length' => 'Пожалуйста, не более 0 символов',
                'min_length' => 'Пожалуйста, не менее 0 символов',
              ),
               'options' => 
              array (
                'required' => false,
                'storage' => 'data',
              ),
               'errors' => 
              array (
              ),
               '_type' => 'waStringValidator',
            )),
          ),
           'name' => 
          array (
            'en_US' => 'City',
          ),
           '_type' => 'waContactStringField',
        )),
        'street' => 
        waContactStringField::__set_state(array(
           'id' => 'street',
           'options' => 
          array (
            'storage' => 'data',
            'validators' => 
            waStringValidator::__set_state(array(
               'messages' => 
              array (
                'required' => 'Required',
                'invalid' => 'Invalid',
                'max_length' => 'Пожалуйста, не более 0 символов',
                'min_length' => 'Пожалуйста, не менее 0 символов',
              ),
               'options' => 
              array (
                'required' => false,
                'storage' => 'data',
              ),
               'errors' => 
              array (
              ),
               '_type' => 'waStringValidator',
            )),
          ),
           'name' => 
          array (
            'en_US' => 'Street address',
          ),
           '_type' => 'waContactStringField',
        )),
        'zip' => 
        waContactStringField::__set_state(array(
           'id' => 'zip',
           'options' => 
          array (
            'storage' => 'data',
            'validators' => 
            waStringValidator::__set_state(array(
               'messages' => 
              array (
                'required' => 'Required',
                'invalid' => 'Invalid',
                'max_length' => 'Пожалуйста, не более 0 символов',
                'min_length' => 'Пожалуйста, не менее 0 символов',
              ),
               'options' => 
              array (
                'required' => false,
                'storage' => 'data',
              ),
               'errors' => 
              array (
              ),
               '_type' => 'waStringValidator',
            )),
          ),
           'name' => 
          array (
            'en_US' => 'ZIP',
          ),
           '_type' => 'waContactStringField',
        )),
        'region' => 
        waContactRegionField::__set_state(array(
           'rm' => NULL,
           'id' => 'region',
           'options' => 
          array (
            'storage' => 'data',
          ),
           'name' => 
          array (
            'en_US' => 'State',
          ),
           '_type' => 'waContactRegionField',
        )),
        'country' => 
        waContactCountryField::__set_state(array(
           'model' => NULL,
           'validate_range' => true,
           'id' => 'country',
           'options' => 
          array (
            'defaultOption' => 'Select country',
            'storage' => 'data',
            'formats' => 
            array (
              'value' => 
              waContactCountryFormatter::__set_state(array(
                 '_type' => 'waContactCountryFormatter',
                 'options' => NULL,
              )),
            ),
          ),
           'name' => 
          array (
            'en_US' => 'Country',
          ),
           '_type' => 'waContactCountryField',
        )),
        'lng' => 
        waContactHiddenField::__set_state(array(
           'id' => 'lng',
           'options' => 
          array (
            'storage' => 'data',
          ),
           'name' => 
          array (
            'en_US' => 'Longitude',
          ),
           '_type' => 'waContactHiddenField',
        )),
        'lat' => 
        waContactHiddenField::__set_state(array(
           'id' => 'lat',
           'options' => 
          array (
            'storage' => 'data',
          ),
           'name' => 
          array (
            'en_US' => 'Latitude',
          ),
           '_type' => 'waContactHiddenField',
        )),
      ),
      'formats' => 
      array (
        'js' => 
        waContactAddressOneLineFormatter::__set_state(array(
           '_type' => 'waContactAddressOneLineFormatter',
           'options' => NULL,
        )),
        'forMap' => 
        waContactAddressForMapFormatter::__set_state(array(
           '_type' => 'waContactAddressForMapFormatter',
           'options' => NULL,
        )),
      ),
      'allow_self_edit' => false,
      'required' => 
      array (
      ),
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Address',
    ),
     '_type' => 'waContactAddressField',
  )),
  5 => 
  waContactUrlField::__set_state(array(
     'id' => 'url',
     'options' => 
    array (
      'multi' => true,
      'ext' => 
      array (
        'work' => 'work',
        'personal' => 'personal',
      ),
      'storage' => 'data',
      'formats' => 
      array (
        'html' => 
        waContactUrlHtmlFormatter::__set_state(array(
           '_type' => 'waContactUrlHtmlFormatter',
           'options' => NULL,
        )),
        'js' => 
        waContactUrlJsFormatter::__set_state(array(
           '_type' => 'waContactUrlJsFormatter',
           'options' => NULL,
        )),
      ),
      'validators' => 
      waUrlValidator::__set_state(array(
         'options' => 
        array (
          'required' => false,
          'pattern' => '`^(https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\\\\\))+([^[:punct:]]|[:#@%/;$()~_?\\+-=\\.&\\\\])*$`iu',
          'multi' => true,
          'ext' => 
          array (
            'work' => 'work',
            'personal' => 'personal',
          ),
          'storage' => 'data',
          'formats' => 
          array (
            'html' => 
            waContactUrlHtmlFormatter::__set_state(array(
               '_type' => 'waContactUrlHtmlFormatter',
               'options' => NULL,
            )),
            'js' => 
            waContactUrlJsFormatter::__set_state(array(
               '_type' => 'waContactUrlJsFormatter',
               'options' => NULL,
            )),
          ),
        ),
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'not_match' => 'Invalid URL',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waUrlValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Website',
    ),
     '_type' => 'waContactUrlField',
  )),
  6 => 
  waContactHiddenField::__set_state(array(
     'id' => 'company_contact_id',
     'options' => 
    array (
      'storage' => 'info',
      'type' => 'Hidden',
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => '',
    ),
     '_type' => 'waContactHiddenField',
  )),
  7 => 
  waContactNameField::__set_state(array(
     'id' => 'name',
     'options' => 
    array (
      'max_length' => 150,
      'storage' => 'info',
      'fconstructor' => 'hidden',
      'required' => false,
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'At least one of these fields must be filled in.',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 150 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => true,
          'max_length' => 150,
          'storage' => 'info',
          'fconstructor' => 'hidden',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Name',
    ),
     '_type' => 'waContactNameField',
  )),
  8 => 
  waContactStringField::__set_state(array(
     'id' => 'title',
     'options' => 
    array (
      'max_length' => 50,
      'storage' => 'info',
      'type' => 'NameSubfield',
      'fconstructor' => 'fixed',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 50 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 50,
          'storage' => 'info',
          'type' => 'NameSubfield',
          'fconstructor' => 'fixed',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Title',
    ),
     '_type' => 'waContactStringField',
  )),
  9 => 
  waContactStringField::__set_state(array(
     'id' => 'firstname',
     'options' => 
    array (
      'max_length' => 50,
      'storage' => 'info',
      'type' => 'NameSubfield',
      'fconstructor' => 'fixed',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 50 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 50,
          'storage' => 'info',
          'type' => 'NameSubfield',
          'fconstructor' => 'fixed',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'First name',
    ),
     '_type' => 'waContactStringField',
  )),
  10 => 
  waContactStringField::__set_state(array(
     'id' => 'middlename',
     'options' => 
    array (
      'max_length' => 50,
      'storage' => 'info',
      'type' => 'NameSubfield',
      'fconstructor' => 'fixed',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 50 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 50,
          'storage' => 'info',
          'type' => 'NameSubfield',
          'fconstructor' => 'fixed',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Middle name',
    ),
     '_type' => 'waContactStringField',
  )),
  11 => 
  waContactStringField::__set_state(array(
     'id' => 'lastname',
     'options' => 
    array (
      'max_length' => 50,
      'storage' => 'info',
      'type' => 'NameSubfield',
      'fconstructor' => 'fixed',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 50 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 50,
          'storage' => 'info',
          'type' => 'NameSubfield',
          'fconstructor' => 'fixed',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Last name',
    ),
     '_type' => 'waContactStringField',
  )),
  12 => 
  waContactStringField::__set_state(array(
     'id' => 'jobtitle',
     'options' => 
    array (
      'max_length' => 50,
      'storage' => 'info',
      'fconstructor' => 'fixed',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 50 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 50,
          'storage' => 'info',
          'fconstructor' => 'fixed',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Job title',
    ),
     '_type' => 'waContactStringField',
  )),
  13 => 
  waContactStringField::__set_state(array(
     'id' => 'company',
     'options' => 
    array (
      'max_length' => 150,
      'storage' => 'info',
      'validators' => 
      waStringValidator::__set_state(array(
         'messages' => 
        array (
          'required' => 'Required',
          'invalid' => 'Invalid',
          'max_length' => 'Пожалуйста, не более 150 символов',
          'min_length' => 'Пожалуйста, не менее 0 символов',
        ),
         'options' => 
        array (
          'required' => false,
          'max_length' => 150,
          'storage' => 'info',
        ),
         'errors' => 
        array (
        ),
         '_type' => 'waStringValidator',
      )),
      'allow_self_edit' => false,
      'required' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Company',
    ),
     '_type' => 'waContactStringField',
  )),
  14 => 
  waContactBranchField::__set_state(array(
     'validate_range' => true,
     'id' => 'sposob-oplaty5',
     'options' => 
    array (
      'app_id' => 'shop',
      'storage' => 'data',
      'options' => 
      array (
        'Наличный расчет' => 'Наличный расчет',
        'Безналичный расчет' => 'Безналичный расчет',
        'Оплата картой Visa/MasterCard' => 'Оплата картой Visa/MasterCard',
        'Online-платеж' => 'Online-платеж',
      ),
      'hide' => 
      array (
      ),
      'required' => false,
      'allow_self_edit' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Способ оплаты',
    ),
     '_type' => 'waContactBranchField',
  )),
  15 => 
  waContactBranchField::__set_state(array(
     'validate_range' => true,
     'id' => 'sposob-oplaty',
     'options' => 
    array (
      'app_id' => 'shop',
      'storage' => 'data',
      'options' => 
      array (
        'Наличный расчет' => 'Наличный расчет',
        'Оплата картой' => 'Оплата картой',
        'Оплата через ЕРИП' => 'Оплата через ЕРИП',
      ),
      'hide' => 
      array (
      ),
      'required' => false,
      'allow_self_edit' => false,
      'unique' => false,
    ),
     'name' => 
    array (
      'en_US' => 'Способ оплаты',
    ),
     '_type' => 'waContactBranchField',
  )),
);

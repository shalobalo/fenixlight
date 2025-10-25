<?php
return array (
  'states' => 
  array (
    'new' => 
    array (
      'name' => 'Новый',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#009900',
          'font-weight' => 'bold',
        ),
        'icon' => 'icon16 ss new',
      ),
      'available_actions' => 
      array (
        0 => 'process',
        1 => 'pay',
        2 => 'ship',
        3 => 'edit',
        4 => 'delete',
        5 => 'complete',
        6 => 'comment',
        7 => 'canceled',
        8 => 'oplata_jdem',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'processing' => 
    array (
      'name' => 'Подтвержден',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#008800',
          'font-style' => 'italic',
        ),
        'icon' => 'icon16 ss new',
      ),
      'available_actions' => 
      array (
        0 => 'pay',
        1 => 'ship',
        2 => 'edit',
        3 => 'delete',
        4 => 'complete',
        5 => 'comment',
        6 => 'config',
        7 => 'canceled',
        8 => 'oplata_jdem',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'config' => 
    array (
      'name' => 'В комплектации',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#f77eea',
        ),
        'icon' => 'icon16 ss flag-blue',
      ),
      'available_actions' => 
      array (
        0 => 'pay',
        1 => 'ship',
        2 => 'refund',
        3 => 'edit',
        4 => 'delete',
        5 => 'complete',
        6 => 'comment',
        7 => 'staffed',
        8 => 'canceled',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'staffed' => 
    array (
      'name' => 'Укомплектован',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#3c79be',
        ),
        'icon' => 'icon16 ss flag-green',
      ),
      'available_actions' => 
      array (
        0 => 'ship',
        1 => 'edit',
        2 => 'delete',
        3 => 'restore',
        4 => 'complete',
        5 => 'canceled',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'paid' => 
    array (
      'name' => 'Оплачен',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#ff9900',
          'font-weight' => 'bold',
          'font-style' => 'italic',
        ),
        'icon' => 'icon16 ss flag-yellow',
      ),
      'available_actions' => 
      array (
        0 => 'process',
        1 => 'ship',
        2 => 'refund',
        3 => 'complete',
        4 => 'comment',
        5 => 'canceled',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'shipped' => 
    array (
      'name' => 'Отправлен',
      'options' => 
      array (
        'icon' => 'icon16 ss sent',
        'style' => 
        array (
          'color' => '#0000FF',
          'font-style' => 'italic',
        ),
      ),
      'available_actions' => 
      array (
        0 => 'complete',
        1 => 'comment',
        2 => 'delete',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'completed' => 
    array (
      'name' => 'Выполнен',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#800080',
        ),
        'icon' => 'icon16 ss completed',
      ),
      'available_actions' => 
      array (
        0 => 'refund',
        1 => 'edit',
        2 => 'comment',
        3 => 'config',
        4 => 'canceled',
        5 => 'oplata_jdem',
      ),
    ),
    'refunded' => 
    array (
      'name' => 'Возврат',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#cc0000',
        ),
        'icon' => 'icon16 ss refunded',
      ),
      'available_actions' => 
      array (
        0 => 'edit',
        1 => 'delete',
        2 => 'restore',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'deleted' => 
    array (
      'name' => 'Удален',
      'options' => 
      array (
        'icon' => 'icon16 ss trash',
        'style' => 
        array (
          'color' => '#aaaaaa',
        ),
      ),
      'available_actions' => 
      array (
        0 => 'restore',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'canceled' => 
    array (
      'name' => 'Отменен',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#8a8a8a',
          'font-style' => 'italic',
        ),
        'icon' => 'icon16 ss refunded',
      ),
      'available_actions' => 
      array (
        0 => 'edit',
        1 => 'delete',
        2 => 'restore',
      ),
      'classname' => 'shopWorkflowState',
    ),
    'oplata_online' => 
    array (
      'name' => 'Ожидает оплаты On-Line',
      'options' => 
      array (
        'style' => 
        array (
          'color' => '#009494',
          'font-weight' => 'bold',
          'font-style' => 'italic',
        ),
        'icon' => 'icon16 ss flag-black',
      ),
      'available_actions' => 
      array (
        0 => 'pay',
        1 => 'ship',
        2 => 'refund',
        3 => 'edit',
        4 => 'delete',
        5 => 'restore',
        6 => 'complete',
        7 => 'comment',
        8 => 'config',
        9 => 'staffed',
        10 => 'canceled',
      ),
      'classname' => 'shopWorkflowState',
    ),
  ),
  'actions' => 
  array (
    'create' => 
    array (
      'classname' => 'shopWorkflowCreateAction',
      'name' => 'Создать',
      'options' => 
      array (
        'log_record' => 'Заказ оформлен',
      ),
      'state' => 'new',
    ),
    'process' => 
    array (
      'classname' => 'shopWorkflowProcessAction',
      'name' => 'В обработку',
      'options' => 
      array (
        'log_record' => 'Заказ подтвержден и принят в обработку',
        'button_class' => 'green',
      ),
      'state' => 'processing',
    ),
    'pay' => 
    array (
      'classname' => 'shopWorkflowPayAction',
      'name' => 'Оплачен',
      'options' => 
      array (
        'log_record' => 'Заказ оплачен',
        'button_class' => 'yellow',
      ),
      'state' => 'paid',
    ),
    'ship' => 
    array (
      'classname' => 'shopWorkflowShipAction',
      'name' => 'Отправлен',
      'options' => 
      array (
        'log_record' => 'Заказ отправлен',
        'button_class' => 'blue',
      ),
      'state' => 'shipped',
    ),
    'refund' => 
    array (
      'classname' => 'shopWorkflowRefundAction',
      'name' => 'Возврат',
      'options' => 
      array (
        'log_record' => 'Возврат',
        'button_class' => 'red',
      ),
      'state' => 'refunded',
    ),
    'edit' => 
    array (
      'classname' => 'shopWorkflowEditAction',
      'name' => 'Редактировать заказ',
      'options' => 
      array (
        'position' => 'top',
        'icon' => 'edit',
        'log_record' => 'Заказ отредактирован',
      ),
    ),
    'delete' => 
    array (
      'classname' => 'shopWorkflowDeleteAction',
      'name' => 'Удалить',
      'options' => 
      array (
        'icon' => 'delete',
        'log_record' => 'Заказ удален',
      ),
      'state' => 'deleted',
    ),
    'restore' => 
    array (
      'classname' => 'shopWorkflowRestoreAction',
      'name' => 'Восстановить',
      'options' => 
      array (
        'icon' => 'restore',
        'log_record' => 'Заказ восстановлен',
        'button_class' => 'green',
      ),
    ),
    'complete' => 
    array (
      'classname' => 'shopWorkflowCompleteAction',
      'name' => 'Выполнен',
      'options' => 
      array (
        'log_record' => 'Заказ выполнен',
        'button_class' => 'purple',
      ),
      'state' => 'completed',
    ),
    'comment' => 
    array (
      'classname' => 'shopWorkflowCommentAction',
      'name' => 'Добавить комментарий',
      'options' => 
      array (
        'position' => 'bottom',
        'icon' => 'add',
        'button_class' => 'inline-link',
        'log_record' => 'Добавлен комментарий к заказу',
      ),
    ),
    'callback' => 
    array (
      'classname' => 'shopWorkflowCallbackAction',
      'name' => 'Ответ платежной системы (callback)',
      'options' => 
      array (
        'log_record' => 'Ответ платежной системы (callback)',
      ),
    ),
    'config' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'В комплектации',
      'state' => 'config',
    ),
    'staffed' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'Укоплектован',
      'state' => 'staffed',
    ),
    'canceled' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'Отменен',
      'state' => 'canceled',
    ),
    'online' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'ONLINE',
      'state' => 'paid',
    ),
    'oplata_robokassa' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'Ожидает оплаты через ROBOKASSA',
    ),
    'oplata_jdem' => 
    array (
      'classname' => 'shopWorkflowAction',
      'name' => 'Ждем оплату',
      'state' => 'oplata_online',
    ),
  ),
);

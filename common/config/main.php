<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],		
	'mail' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@common/mail',
        'useFileTransport' => false,//set this property to false to send mails to real email addresses
        //comment the following array to send mail using php's mail function 		
    ],    	
    ],
    'modules' => [        
		'admin' => [
            'class' => 'mdm\admin\Module',
        ],
	],
    'on beforeRequest'  => function ($event) {
        Yii::$container->set('yii\grid\DataColumn', [
            'filterInputOptions' => [
                'class'       => 'form-control',
                'placeholder' => '--Search--'
            ]
        ]);
    },
];

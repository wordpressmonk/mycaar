<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'timeZone' =>'asia/kolkata',
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
			'transport' => [
          		 'class' => 'Swift_SmtpTransport',
           		 'host' => 'mycaar.com.au',
           		 'username' => 'support@mycaar.com.au',
           		 'password' => 'support@2017',
           		 'port' => '465',
           		 'encryption' => 'ssl', 
                        ], 
						
		],   
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=mycaar_v2',
            'username' => 'mycaar_v2user',
            'password' => 'mycaar@2017v2',
            'charset' => 'utf8',
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

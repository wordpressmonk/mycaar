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
		 'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,			
        ],
    ],
    'modules' => [        
		'admin' => [
            'class' => 'mdm\admin\Module',
        ],
	]
];

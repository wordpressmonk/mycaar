<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
	'modules' =>[
	'gii' => [
            'class' => 'yii\gii\Module',
        ],
	]	
];

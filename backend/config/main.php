<?php
use \yii\web\Request;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$frontEndBaseUrl = str_replace('/backend/web', '/frontend/web', (new Request)->getBaseUrl());

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
	'defaultRoute' => 'course/report/search',
    'bootstrap' => ['log'],
    'layout' => 'dashboard',
    'modules' => [
      'course' => [
          'class' => 'backend\modules\course\Course',
      ],
      'user' => [
          'class' => 'backend\modules\user\module',
      ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
			'enableCsrfValidation'=>false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		 'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => $frontEndBaseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ], 

    ],
    'params' => $params,
];

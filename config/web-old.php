<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';

$config = [
    'id' => 'ems',
    'name' => 'EMS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@app/node_modules',
    ],
    'components' => [
//        'authManager' => [
//            'class' => 'yii\rbac\DbManager',
//            'defaultRoles' => ['Guest']
//        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '7VM7ufGea8-jM5cS6zRMI9euOJkF3XUU',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => yii\rest\UrlRule::class, 'controller' => ['v1/device']],
            ],
        ],
        'assetManager' => [
            // override bundles to use local project files :
            'bundles' => [
                'yii\bootstrap4\BootstrapAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ],
            ],
        ],
//        'view' => [
////            'theme' => [
////                'basePath' => '@app/themes/materializecss',
////                'baseUrl' => '@web/themes/materializecss',
////                'pathMap' => [
////                    '@app/views' => [
////                        '@app/themes/materializecss/views'
////                    ],
//////                    '@vendor/mdmsoft/yii2-admin/views' => '@app/modules/backend/views/mdmsoft-rbac'
////                ]
////            ],
//            'theme' => [
//                'basePath' => '@app/themes/materialbootstrap',
//                'baseUrl' => '@web/themes/materialbootstrap',
//                'pathMap' => [
//                    '@app/views' => [
//                        '@app/themes/materialbootstrap/views'
//                    ],
////                    '@vendor/mdmsoft/yii2-admin/views' => '@app/modules/backend/views/mdmsoft-rbac'
//                ]
//            ]
//        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'params' => $params,
];

if (file_exists(__DIR__ . '/debug-gii.php')) {
    require __DIR__ . '/debug-gii.php';
}

return $config;

<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
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
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/jogo', 'api/pergunta', 'api/resposta', 'api/user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {id}/perguntas' => 'perguntas',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/pergunta'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {id}/respostas' => 'respostas',
                    ],
                ],
                'api/favorito' => 'api/favorito/index',
                'api/favorito/<userId:\d+>' => 'api/favorito/user',
            ],

        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/hail812/yii2-adminlte3/src/views' => '@backend/views'
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => [
            'site/login',
            'site/error',
            'site/logout',
            'api/*',
        ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['admin', 'moderador'],
            ],
        ],
    ],
    'params' => $params,
];
<?php

namespace backend\modules\api;

use yii\web\Response;
use yii\filters\ContentNegotiator;

class ModuleAPI extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\api\controllers';

    public function init()
    {
        parent::init();

        \Yii::$app->user->enableSession = false;

        \Yii::$app->response->format = Response::FORMAT_JSON;

        \Yii::$app->response->on(
            Response::EVENT_BEFORE_SEND,
            function () {
                \Yii::$app->response->headers->set('Content-Type', 'application/json; charset=UTF-8');
            }
        );
    }
}


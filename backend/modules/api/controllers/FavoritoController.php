<?php

namespace backend\modules\api\controllers;

use yii\rest\Controller; // não ActiveController
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\Favoritos;

class FavoritoController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        return Favoritos::find()->all();
    }

    public function actionUser($userId)
    {
        return Favoritos::find()->where(['user_id' => $userId])->all();
    }
}

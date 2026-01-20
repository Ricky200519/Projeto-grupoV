<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Jogo;
use common\models\Pergunta;
use yii\web\NotFoundHttpException;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class JogoController extends ActiveController
{
    public $modelClass = Jogo::class;

    public function actionPerguntas($id)
    {
        $jogo = Jogo::findOne($id);

        if ($jogo === null) {
            throw new NotFoundHttpException('Jogo não encontrado');
        }

        return Pergunta::find()
            ->where(['jogo_id' => $id])
            ->all();
    }

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
}

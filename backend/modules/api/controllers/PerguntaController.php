<?php

namespace backend\modules\api\controllers;

use common\models\Resposta;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\Pergunta;
use yii\web\NotFoundHttpException;

class PerguntaController extends ActiveController
{
    public $modelClass = Pergunta::class;

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

    public function actionRespostas($id)
    {
        $pergunta = Pergunta::findOne($id);

        if ($pergunta === null) {
            throw new NotFoundHttpException('Pergunta não encontrada');
        }

        return resposta::find()
            ->where(['pergunta_id' => $id])
            ->all();
    }
}

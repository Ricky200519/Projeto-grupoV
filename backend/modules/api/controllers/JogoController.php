<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Jogo;

class JogoController extends ActiveController
{
    public $modelClass = Jogo::class;

    public function actionPerguntas($id)
    {
        return \common\models\Pergunta::find()->where(['jogo_id' => $id])->all();
    }


}
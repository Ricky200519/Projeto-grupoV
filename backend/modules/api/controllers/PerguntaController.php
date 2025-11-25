<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Pergunta;

class PerguntaController extends ActiveController
{
    public $modelClass = Pergunta::class;
}
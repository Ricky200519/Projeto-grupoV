<?php

namespace backend\modules\api\controllers;


use yii\rest\ActiveController;
use common\models\Resposta;

class RespostaController extends ActiveController
{
    public $modelClass = Resposta::class;
}
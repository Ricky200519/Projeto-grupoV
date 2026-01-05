<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Pergunta;

class PerguntaController extends ActiveController
{
    public $modelClass = Pergunta::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'only' => ['index', ], // Apenas para o GET
            // 'auth' => [$this, 'auth']   // da erro ao na web

        ];
        return $behaviors;
    }


    public function auth($username, $password)
    {
        $user = \app\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }
}
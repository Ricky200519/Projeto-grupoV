<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use common\models\Pergunta;

class PerguntaController extends ActiveController
{
    public $modelClass = Pergunta::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['index', 'view'],

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
        throw new \yii\web\ForbiddenHttpException('Sem autenticação');
    }
}
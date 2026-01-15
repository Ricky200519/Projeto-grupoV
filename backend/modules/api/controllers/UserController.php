<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;
use common\models\User;
use yii\filters\auth\QueryParamAuth;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['index', 'view'],

        ];
        return $behaviors;
    }

    //metodo autenticação que verifica se o user existe e a password esta correta
    public function auth($username, $password)
    {
        $user = \app\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('Sem autenticacao');
    }


}

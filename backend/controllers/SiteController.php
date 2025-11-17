<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['admin', 'moderador'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->can('admin') && !Yii::$app->user->can('moderador')) {
            Yii::$app->user->logout();
            return $this->redirect(['site/login']);
        }


        $accessDeniedMessage = Yii::$app->session->get('accessDeniedMessage');
        if ($accessDeniedMessage) {
            Yii::$app->session->remove('accessDeniedMessage');
            return $this->render('index', [
                'accessDenied' => true,
                'accessDeniedMessage' => $accessDeniedMessage
            ]);
        }

        return $this->render('index');
    }

    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }


        $this->layout = 'login';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                // Verificar permissões de backend
                if (Yii::$app->user->can('admin') || Yii::$app->user->can('moderador')) {
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('error',
                        '<strong>Acesso Negado</strong><br>
                        You do not have permission to access the backend. Only administrators and moderators can access this area.'
                    );
                }
            } else {
                Yii::$app->session->setFlash('error',
                    'Incorrect username or password. Please try again.'
                );
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}

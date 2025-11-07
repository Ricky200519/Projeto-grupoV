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
    /**
     * Comportamentos de acesso e métodos HTTP permitidos.
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // Login e erro acessíveis a visitantes
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    // Logout e index acessíveis apenas a administradores e moderadores
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

    /**
     * Ações personalizadas.
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Página inicial do backend.
     */
    public function actionIndex()
    {
        // Verificação de segurança adicional
        if (!Yii::$app->user->can('admin') && !Yii::$app->user->can('moderador')) {
            Yii::$app->user->logout();
            return $this->redirect(['site/login']);
        }

        // Verificar se há mensagem de acesso negado na sessão
        $accessDeniedMessage = Yii::$app->session->get('accessDeniedMessage');
        if ($accessDeniedMessage) {
            // Limpar a mensagem da sessão após usar
            Yii::$app->session->remove('accessDeniedMessage');

            return $this->render('index', [
                'accessDenied' => true,
                'accessDeniedMessage' => $accessDeniedMessage
            ]);
        }

        return $this->render('index');
    }

    /**
     * Página de login.
     */
    public function actionLogin()
    {
        // Se o utilizador já está autenticado, redirecionar para o painel
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        // Usar layout de login simples
        $this->layout = 'main-login';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                // Verificar permissões de backend
                if (Yii::$app->user->can('admin') || Yii::$app->user->can('moderador')) {
                    return $this->redirect(['index']);
                } else {
                    // Não tem permissão - fazer logout e mostrar erro
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('error',
                        '<strong>Acesso Negado</strong><br>
                        Não tem permissões para aceder ao backend. 
                        Apenas administradores e moderadores podem aceder a esta área.'
                    );
                }
            } else {
                // Erro de login normal (credenciais inválidas)
                Yii::$app->session->setFlash('error',
                    'Username ou password incorretos. Por favor, tente novamente.'
                );
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Termina a sessão e redireciona para o login.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}
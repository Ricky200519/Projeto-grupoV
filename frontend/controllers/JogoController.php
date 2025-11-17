<?php

namespace frontend\controllers;

use common\models\Jogo;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class JogoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['quizCreate'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['quizUpdate'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['quizDelete'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('error', 'Não tens permissão para aceder a esta página.');
                    return $this->redirect(['index']);
                }
            ],
        ];
    }


    /**
     * Lists all Jogo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->view->title = 'Quizzes';

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $userId = Yii::$app->user->id;

        $meusJogos = Yii::$app->user->identity->jogos;
        /*meusJogos = \common\models\Jogo::find()
            ->where(['autor_id' => $userId])
            ->orderBy(['datacriacao' => SORT_DESC])
            ->all();*/

        $publicos = \common\models\Jogo::find()
            ->where(['IsPublic' => 1])
            ->andWhere(['<>', 'autor_id', $userId])
            ->orderBy(['datacriacao' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'meusJogos' => $meusJogos,
            'publicos' => $publicos,
        ]);
    }


    /**
     * Displays a single Jogo model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = Jogo::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Quiz não encontrado.');
        }


        $perguntas = $model->perguntas;

        return $this->render('view', [
            'model' => $model,
            'perguntas' => $perguntas,
        ]);

    }

    /**
     * Creates a new Jogo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Jogo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // Definir dados automáticos
                $model->autor_id = Yii::$app->user->id;
                $model->datacriacao = date('Y-m-d H:i:s');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Quiz criado com sucesso!');
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Jogo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Jogo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Jogo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Jogo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jogo::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}

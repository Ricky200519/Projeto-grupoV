<?php

namespace frontend\controllers;

use common\models\Pergunta;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PerguntaController implements the CRUD actions for Pergunta model.
 */
class PerguntaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Pergunta models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Pergunta::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pergunta model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pergunta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($jogo_id)
    {
        $model = new \common\models\Pergunta();
        $model->jogo_id = $jogo_id;
        $existingCount = \common\models\Pergunta::find()
            ->where(['jogo_id' => $jogo_id])
            ->count();
        $nextNumber = $existingCount + 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', 'Pergunta criada com sucesso!');

            // Ir criar respostas
            return $this->redirect(['resposta/create', 'pergunta_id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'jogo_id' => $jogo_id,
            'nextNumber' => $nextNumber,
        ]);

    }






    /**
     * Updates an existing Pergunta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $jogo_id = null)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($jogo_id !== null) {
                return $this->redirect(['jogo/view', 'id' => $jogo_id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'jogo_id' => $jogo_id,
        ]);
    }


    /**
     * Deletes an existing Pergunta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $jogoId = $model->jogo_id;
        $model->delete();
        return $this->redirect(['jogo/view', 'id' => $jogoId]);
    }


    /**
     * Finds the Pergunta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Pergunta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pergunta::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace frontend\controllers;

use common\models\Pergunta;
use common\models\Resposta;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RespostaController implements the CRUD actions for Resposta model.
 */
class RespostaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Resposta models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $query = \frontend\models\Jogo::find()
            ->where(['autor_id' => Yii::$app->user->id])
            ->orWhere(['IsPublic' => 1])
            ->orderBy(['datacriacao' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);

        Yii::$app->view->title = 'Os Meus jogos';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Resposta model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pergunta_id)
    {
        $pergunta = Pergunta::findOne($pergunta_id);
        if (!$pergunta) {
            throw new NotFoundHttpException('Pergunta não encontrada.');
        }

        $respostas = Resposta::find()->where(['pergunta_id' => $pergunta_id])->all();

        return $this->render('view', [
            'pergunta' => $pergunta,
            'respostas' => $respostas,
        ]);
    }

    /**
     * Creates a new Resposta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($pergunta_id)
    {
        $pergunta = Pergunta::findOne($pergunta_id);
        if (!$pergunta) {
            throw new NotFoundHttpException('Pergunta não encontrada.');
        }

        $jogo_id = $pergunta->jogo_id;
        $total = Resposta::find()->where(['pergunta_id' => $pergunta_id])->count();
        $existeCorreta = Resposta::find()->where(['pergunta_id' => $pergunta_id, 'correta' => 1])->exists();
        if ($total >= 4) {
            Yii::$app->session->setFlash('error', 'Esta pergunta já tem 4 respostas.');
            return $this->redirect(['view', 'pergunta_id' => $pergunta_id]);
        }

        $model = new Resposta();
        $model->pergunta_id = $pergunta_id;

        if ($model->load(Yii::$app->request->post())) {
            $total = Resposta::find()->where(['pergunta_id' => $pergunta_id])->count();
            $existeCorreta = Resposta::find()->where(['pergunta_id' => $pergunta_id, 'correta' => 1])->exists();

            if ($model->correta == 1 && $existeCorreta) {
                Yii::$app->session->setFlash('error', 'Já existe uma resposta correta nesta pergunta.');
                $model->correta = 0;
            }
            if ($total == 3 && !$existeCorreta) {
                $model->correta = 1;
            }

            if ($model->save()) {

                if (Yii::$app->request->post('add-answer') !== null) {
                    return $this->redirect(['create', 'pergunta_id' => $pergunta_id]);
                }

                if (Yii::$app->request->post('new-question') !== null) {
                    if ($total + 1 < 2) {
                        Yii::$app->session->setFlash('error', 'Cada pergunta deve ter pelo menos 2 respostas.');
                        return $this->redirect(['create', 'pergunta_id' => $pergunta_id]);
                    }
                    return $this->redirect(['/pergunta/create', 'jogo_id' => $pergunta->jogo_id]);
                }

                if (Yii::$app->request->post('finish') !== null) {
                    if ($total + 1 < 2) {
                        Yii::$app->session->setFlash('error', 'Não podes finalizar. Cada pergunta deve ter pelo menos 2 respostas.');
                        return $this->redirect(['create', 'pergunta_id' => $pergunta_id]);
                    }
                    return $this->redirect(['view', 'pergunta_id' => $pergunta_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'pergunta' => $pergunta,
            'jogo_id' => $jogo_id,
            'total' => $total,
            'existeCorreta' => $existeCorreta,
            'isUpdate' => false,
        ]);
    }

    /**
     * Updates an existing Resposta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $pergunta = $model->pergunta;
        $total = Resposta::find()->where(['pergunta_id' => $pergunta->id])->count();
        $jogo_id = $pergunta->jogo_id;

        $total = null;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pergunta_id' => $model->pergunta_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'pergunta' => $model->pergunta,
            'jogo_id' => $model->pergunta->jogo_id,
            'total' => $total,
            'isUpdate' => true,
        ]);
    }

    /**
     * Deletes an existing Resposta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $pergunta_id = $model->pergunta_id;
        $model->delete();

        return $this->redirect(['resposta/view', 'pergunta_id' => $pergunta_id]);
    }

    /**
     * Finds the Resposta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Resposta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resposta::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBackToGame($pergunta_id)
    {
        $pergunta = Pergunta::findOne($pergunta_id);
        if (!$pergunta) {
            throw new NotFoundHttpException('Pergunta não encontrada.');
        }

        $respostas = Resposta::find()->where(['pergunta_id' => $pergunta_id])->all();
        $respostasCount = count($respostas);
        $totalCorretas = Resposta::find()->where(['pergunta_id' => $pergunta_id, 'correta' => 1])->count();

        if (($respostasCount != 2 && $respostasCount != 4) || $totalCorretas != 1) {
            Yii::$app->session->setFlash('error', 'Não podes voltar ao jogo até cada pergunta ter 2 ou 4 respostas e exatamente 1 correta.');
            return $this->redirect(['view', 'pergunta_id' => $pergunta_id]);
        }

        return $this->redirect(['jogo/view', 'id' => $pergunta->jogo_id]);
    }

}

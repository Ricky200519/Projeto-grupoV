<?php

namespace backend\controllers;

use Yii;
use common\models\Pergunta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PerguntaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate($jogo_id)
    {
        $model = new \common\models\Pergunta();
        $model->jogo_id = $jogo_id;
        $existingCount = \common\models\Pergunta::find()->where(['jogo_id' => $jogo_id])->count();
        $nextNumber = $existingCount + 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Pergunta criada com sucesso!');
            return $this->redirect(['quiz/view', 'id' => $jogo_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'jogo_id' => $jogo_id,
            'nextNumber' => $nextNumber,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['quiz/view', 'id' => $model->jogo_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $jogoId = $model->jogo_id;
        $model->delete();
        return $this->redirect(['quiz/view', 'id' => $jogoId]);
    }

    protected function findModel($id)
    {
        if (($model = Pergunta::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Pergunta não encontrada.');
    }
}

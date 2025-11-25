<?php

namespace frontend\controllers;

use common\models\Jogo;
use common\models\Pergunta;
use common\models\Tentativa;
use common\models\OpcaoEscolhida;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;

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
                'only' => ['create', 'update', 'delete', 'view'],
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
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['quizView'],
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

        $publicos = Jogo::find()
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
     * Start a new quiz attempt.
     *
     * @param int $jogo_id
     * @return \yii\web\Response
     */
    public function actionStart($jogo_id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $jogo = Jogo::findOne($jogo_id);
        if (!$jogo) {
            throw new NotFoundHttpException('Jogo não encontrado.');
        }

        $tentativa = new Tentativa();
        $tentativa->jogador_id = Yii::$app->user->id;
        $tentativa->jogo_id = $jogo_id;
        $tentativa->datahora = date('Y-m-d H:i:s');
        $tentativa->save();

        $primeiraPergunta = $jogo->perguntas[0] ?? null;
        if ($primeiraPergunta) {
            return $this->redirect(['pergunta', 'tentativa_id' => $tentativa->id, 'pergunta_id' => $primeiraPergunta->id]);
        }

        Yii::$app->session->setFlash('error', 'Não existem perguntas neste quiz.');
        return $this->redirect(['index']);
    }

    /**
     * Apresenta o quiz antes de iniciar.
     *
     * @param int $jogo_id
     * @return string
     */
    public function actionApresentacao($jogo_id)
    {
        $jogo = Jogo::findOne($jogo_id);
        if (!$jogo) {
            throw new NotFoundHttpException('Jogo não encontrado.');
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $totalPerguntas = Pergunta::find()->where(['jogo_id' => $jogo_id])->count();

        return $this->render('apresentacao', [
            'jogo' => $jogo,
            'totalPerguntas' => $totalPerguntas,
        ]);
    }

    /**
     * Handle question display and answer processing.
     *
     * @param int $tentativa_id
     * @param int $pergunta_id
     * @return string
     */
    public function actionPergunta($tentativa_id, $pergunta_id)
    {
        $tentativa = Tentativa::findOne($tentativa_id);
        //$tentativa->jogo->perguntas;
        if (!$tentativa || $tentativa->jogador_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Tentativa inválida.');
        }

        $pergunta = Pergunta::findOne($pergunta_id);
        if (!$pergunta) {
            throw new NotFoundHttpException('Pergunta não encontrada.');
        }

        $respostas = $pergunta->respostas;

        $proximaPergunta = Pergunta::find()
            ->where(['jogo_id' => $pergunta->jogo_id])
            ->andWhere(['>', 'id', $pergunta->id])
            ->orderBy(['id' => SORT_ASC])
            ->one();

        $isUltimaPergunta = $proximaPergunta === null;
        $proximaPerguntaId = $proximaPergunta ? $proximaPergunta->id : null;

        if (Yii::$app->request->isPost) {
            $respostaEscolhidaId = Yii::$app->request->post('resposta_id');
            if ($respostaEscolhidaId) {
                $opcao = new OpcaoEscolhida();
                $opcao->resposta_id = $respostaEscolhidaId;
                $opcao->tentativa_id = $tentativa->id;
                $opcao->jogador_id = Yii::$app->user->id;
                $opcao->pergunta_id = $pergunta->id;
                $opcao->datahora = date('Y-m-d H:i:s');
                $opcao->save();
            }

            if ($isUltimaPergunta) {
                return $this->redirect(['finish', 'tentativa_id' => $tentativa->id]);
            } else {
                return $this->redirect(['pergunta', 'tentativa_id' => $tentativa->id, 'pergunta_id' => $proximaPerguntaId]);
            }
        }

        return $this->render('pergunta', [
            'tentativa' => $tentativa,
            'pergunta' => $pergunta,
            'respostas' => $respostas,
            'isUltimaPergunta' => $isUltimaPergunta,
            'proximaPerguntaId' => $proximaPerguntaId,
        ]);
    }
    /**
     * Finaliza a tentativa e calcula a pontuação.
     * @param int $tentativa_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFinish($tentativa_id)
    {
        $tentativa = Tentativa::findOne($tentativa_id);
        if (!$tentativa || $tentativa->jogador_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Tentativa inválida.');
        }

        $opcoesEscolhidas = OpcaoEscolhida::find()
            ->where(['tentativa_id' => $tentativa->id])
            ->all();

        $score = 0;

        foreach ($opcoesEscolhidas as $opcao) {
            if ($opcao->resposta->correta == 1) {
                $score += $opcao->resposta->pergunta->pontosvalor;
            }
        }

        return $this->render('finish', [
            'tentativa' => $tentativa,
            'score' => $score,
        ]);
    }
    /**
     * Finalizes the quiz and shows the score.
     *
     * @param int $tentativa_id
     * @return string
     */

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

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}

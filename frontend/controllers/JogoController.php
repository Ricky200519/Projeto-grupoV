<?php

namespace frontend\controllers;

use common\models\Jogo;
use common\models\Pergunta;
use common\models\Tentativa;
use common\models\OpcaoEscolhida;
use yii\data\ArrayDataProvider;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;
use common\models\Jogador;
use common\models\Rating;

class JogoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'view', 'apresentacao', 'pergunta', 'finish'],
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
                    [
                        'actions' => ['apresentacao', 'pergunta', 'finish'],
                        'allow' => true,
                        'roles' => ['quizPlay'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('error', 'Não tens permissão para aceder a esta página.');
                    return $this->redirect(['index']);
                }
            ],
        ];
    }
    public function actionIndex()
    {
        Yii::$app->view->title = 'Quizzes';
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }
        $userId = Yii::$app->user->id;
        $meusJogos = Yii::$app->user->identity->jogos;
        $ordenar = Yii::$app->request->get('ordenar', 'nome');
        $query = Jogo::find()
            ->where(['isPublic' => 1])
            ->andWhere(['<>', 'autor_id', $userId]);
        if ($ordenar === 'tentativas') {
        } else {
            $query->orderBy(['titulo' => SORT_ASC]);
        }
        $publicos = $query->all();
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
        if (!Yii::$app->user->can('quizPlay') || Yii::$app->user->identity->isAdmin()) {
            Yii::$app->session->setFlash('error', 'Não tens permissão para jogar.');
            return $this->redirect(['index']);
        }
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
        if (!$tentativa || $tentativa->jogador_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Tentativa inválida.');
        }
        $pergunta = Pergunta::findOne($pergunta_id);
        if (!$pergunta) {
            throw new NotFoundHttpException('Pergunta não encontrada.');
        }
        $respostas = $pergunta->respostas;
        $perguntas = Pergunta::find()
            ->where(['jogo_id' => $pergunta->jogo_id])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $totalPerguntas = count($perguntas);
        $numeroAtual = 1;
        foreach ($perguntas as $i => $p) {
            if ($p->id == $pergunta->id) {
                $numeroAtual = $i + 1;
                break;
            }
        }
        $proximaPergunta = Pergunta::find()
            ->where(['jogo_id' => $pergunta->jogo_id])
            ->andWhere(['>', 'id', $pergunta->id])
            ->orderBy(['id' => SORT_ASC])
            ->one();
        $isUltimaPergunta = !$proximaPergunta;
        $proximaPerguntaId = $proximaPergunta ? $proximaPergunta->id : null;
        if (Yii::$app->request->isPost) {
            $respostaEscolhidaId = Yii::$app->request->post('resposta_id');
            $opcao = new OpcaoEscolhida();
            $opcao->resposta_id = $respostaEscolhidaId ?: null;
            $opcao->tentativa_id = $tentativa->id;
            $opcao->jogador_id = Yii::$app->user->id;
            $opcao->pergunta_id = $pergunta->id;
            $opcao->datahora = date('Y-m-d H:i:s');
            $opcao->save(false);
            if ($isUltimaPergunta) {
                return $this->redirect(['finish', 'tentativa_id' => $tentativa->id]);
            }
            return $this->redirect([
                'pergunta',
                'tentativa_id' => $tentativa->id,
                'pergunta_id' => $proximaPerguntaId
            ]);
        }
        return $this->render('pergunta', [
            'tentativa' => $tentativa,
            'pergunta' => $pergunta,
            'respostas' => $respostas,
            'isUltimaPergunta' => $isUltimaPergunta,
            'proximaPerguntaId' => $proximaPerguntaId,
            'numeroAtual' => $numeroAtual,
            'totalPerguntas' => $totalPerguntas,
        ]);
    }

    public function actionFinish($tentativa_id)
    {
        $tentativa = \common\models\Tentativa::findOne($tentativa_id);

        if (!$tentativa || $tentativa->jogador_id != Yii::$app->user->id) {
            throw new \yii\web\NotFoundHttpException("Tentativa inválida");
        }

        $pontuacao = $tentativa->calcularPontuacao();
        $jogador = \common\models\Jogador::findOne([
            'user_id' => Yii::$app->user->id,
            'jogo_id' => $tentativa->jogo_id
        ]);

        if (!$jogador) {
            $jogador = new \common\models\Jogador();
            $jogador->user_id = Yii::$app->user->id;
            $jogador->jogo_id = $tentativa->jogo_id;
            $jogador->pontuacao = $pontuacao['totalPontos'];
        } elseif ($pontuacao['totalPontos'] > $jogador->pontuacao) {
            $jogador->pontuacao = $pontuacao['totalPontos'];
        }

        $jogador->data_ultima_tentativa = date('Y-m-d H:i:s');
        $jogador->save();

        $ratingExistente = $tentativa->jogo->getUserRating(Yii::$app->user->id);

        $ranking = $tentativa->jogo->getRanking();

        return $this->render('finish', [
            'tentativa' => $tentativa,
            'pontuacao' => $pontuacao,
            'jogador' => $jogador,
            'ranking' => $ranking,
            'ratingExistente' => $ratingExistente,
        ]);
    }

    public function actionStats($jogo_id)
    {
        $jogo = Jogo::findOne($jogo_id);
        $userId = Yii::$app->user->id;
        $tentativas = Tentativa::find()->where(['jogo_id' => $jogo_id, 'jogador_id' => $userId])->count();
        $melhorPontuacao = Jogador::find()->where(['jogo_id' => $jogo_id, 'user_id' => $userId])->max('pontuacao');
        $ranking = Jogador::find()->where(['jogo_id' => $jogo_id])->orderBy(['pontuacao' => SORT_DESC])->all();
        $posicao = null;
        $rank = 1;
        foreach ($ranking as $j) {
            if ($j->user_id == $userId) {
                $posicao = $rank;
                break;
            }
            $rank++;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $ranking,
            'pagination' => ['pageSize' => 10],
        ]);
        return $this->render('stats', compact('jogo', 'tentativas', 'melhorPontuacao', 'posicao', 'dataProvider'));
    }

    public function actionRating()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Precisas de iniciar sessão.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $request = Yii::$app->request;
        if (!$request->isPost) {
            Yii::$app->session->setFlash('error', 'Pedido inválido.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $jogoId = (int)$request->post('jogo_id');
        $estrelas = (int)$request->post('estrelas');
        $userId = Yii::$app->user->id;

        if ($estrelas < 1 || $estrelas > 5) {
            Yii::$app->session->setFlash('error', 'Número de estrelas inválido.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $rating = \common\models\Rating::findOne(['user_id' => $userId, 'jogo_id' => $jogoId]);
        if (!$rating) {
            $rating = new \common\models\Rating();
            $rating->user_id = $userId;
            $rating->jogo_id = $jogoId;
        }

        $rating->estrelas = $estrelas;
        $rating->dataavaliacao = date('Y-m-d H:i:s');

        if ($rating->save()) {
            Yii::$app->session->setFlash('success', 'Avaliação guardada com sucesso!');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao guardar a avaliação.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionAdicionarFavorito($jogo_id)
    {
        $userId = Yii::$app->user->id;

        if (!\common\models\Favoritos::find()->where(['user_id' => $userId, 'jogo_id' => $jogo_id])->exists()) {
            $fav = new \common\models\Favoritos();
            $fav->user_id = $userId;
            $fav->jogo_id = $jogo_id;
            $fav->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemoverFavorito($jogo_id)
    {
        $userId = Yii::$app->user->id;

        $fav = \common\models\Favoritos::find()->where(['user_id' => $userId, 'jogo_id' => $jogo_id])->one();
        if ($fav) {
            $fav->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        if (($model = Jogo::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}
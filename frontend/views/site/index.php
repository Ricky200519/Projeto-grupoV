<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use common\models\Pergunta;
use common\models\Tentativa;
use common\models\Jogador;

$this->title = 'LearnQuiz';
$this->registerCssFile("css/index.css");
usort($publicos, fn($a, $b) => ($b->mediaRating <=> $a->mediaRating));
function obterPosicaoRanking($jogoId, $userId)
{
    $ranking = Jogador::find()->where(['jogo_id' => $jogoId])->orderBy(['pontuacao' => SORT_DESC])->all();
    $pos = 1;
    foreach ($ranking as $j) {
        if ($j->user_id == $userId) {
            return $pos;
        }
        $pos++;
    }
    return null;
}

$publicos = array_slice($publicos, 0, 2);

?>
<div class="main-card bg-white border border-primary border-2 mt-4">
    <div class="main-index">
        <h2 class="text-secondary">
            Bem-vindo ao <span class="text-primary"><?= Html::encode($this->title) ?>
                <?php if (!Yii::$app->user->isGuest): ?></span>
            <?= Html::encode(Yii::$app->user->identity->username) ?>!
            <?php endif; ?>
        </h2>
    </div>

    <div class="quiz-container card bg-white text-light mt-3 p-4 mx-auto w-100 border border-primary border-2">
        <h3 class="mb-4 text-primary text-start">Jogo Rápido</h3>

        <h5 class="text-secondary">Aqui podes escolher um jogo para jogar rapidamente ou criar o teu próprio jogo!</h5>
        <br>

        <div class="d-flex flex-wrap gap-4">
            <?php $userId = Yii::$app->user->id; ?>

            <a href="<?= Yii::$app->user->isGuest ? '#' : yii\helpers\Url::to(['jogo/create']) ?>"
               class="create-link text-decoration-none"
               data-bs-toggle="<?= Yii::$app->user->isGuest ? 'modal' : '' ?>"
               data-bs-target="<?= Yii::$app->user->isGuest ? '#loginModal' : '' ?>">
                <div class="card quiz-card create-card text-center d-flex align-items-center justify-content-center "
                     style="width: 18rem; cursor:pointer;">
                    <div>
                        <div class="plus-icon">+</div>
                        <h5 class="text-secondary mt-2">Cria o teu jogo</h5>
                    </div>
                </div>
            </a>

            <?php foreach ($publicos as $jogo): ?>
                <?php
                $numPerguntas = Pergunta::find()->where(['jogo_id' => $jogo->id])->count();
                $tentativasUser = Yii::$app->user->isGuest ? 0 : Tentativa::find()->where(['jogo_id' => $jogo->id, 'jogador_id' => $userId])->count();
                $posicao = $tentativasUser > 0 ? obterPosicaoRanking($jogo->id, $userId) : null;
                $corMedalha = match ($posicao) {
                    1 => 'gold',
                    2 => 'silver',
                    3 => '#cd7f32',
                    default => null
                };
                ?>
                <a href="<?= Yii::$app->user->isGuest ? '#' : yii\helpers\Url::to(['jogo/apresentacao', 'jogo_id' => $jogo->id]) ?>"
                   data-bs-toggle="<?= Yii::$app->user->isGuest ? 'modal' : '' ?>"
                   data-bs-target="<?= Yii::$app->user->isGuest ? '#loginModal' : '' ?>"
                   class="text-decoration-none">
                    <div class="card quiz-card text-dark" style="width: 18rem;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="card-title fw-bold text-secondary mb-0"><?= Html::encode($jogo->titulo) ?></h5>
                                <?php if ($posicao && $posicao <= 3): ?>
                                    <span class="fw-bold fas fa-trophy"
                                          style="color: <?= $corMedalha ?>;">Top <?= $posicao ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="card-text text-secondary mb-2"><?= Html::encode($jogo->descricao ?: 'Sem descrição') ?></p>

                            <p class="card-text mb-1">
                                <small class="text-secondary">
                                    Criado por: <?= Html::encode($jogo->autor->username ?? 'Desconhecido') ?> |
                                    <?= Yii::$app->formatter->asDate($jogo->datacriacao, 'php:d/m/Y') ?>
                                </small>
                            </p>
                            <p class="card-text mb-1"><small class="text-secondary"><?= $numPerguntas ?>
                                    perguntas</small>
                            </p>
                            <p class="card-text mb-2">
                                <small class="<?= $tentativasUser > 0 ? 'text-primary' : 'text-secondary' ?>">
                                    <?= $tentativasUser > 0 ? 'Já jogaste' : 'Ainda não jogaste este quiz' ?>
                                </small>
                            </p>
                            <div class="d-grid">
                                <button class="btn btn-primary fw-bold w-100"
                                        <?= Yii::$app->user->isGuest ? 'data-bs-toggle="modal" data-bs-target="#loginModal"' : '' ?>>
                                    Jogar
                                </button>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light border-secondary rounded-3">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-primary" id="loginRequiredModalLabel">Acesso necessário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                    Para criares um quiz ou jogar, precisas de estar autenticado!<br>
                    Inicia sessão ou cria uma conta para continuar.
                </div>
                <div class="modal-footer border-secondary d-flex justify-content-center">
                    <a href="<?= yii\helpers\Url::to(['site/login']) ?>" class="btn btn-primary me-2 px-4">Login</a>
                    <a href="<?= yii\helpers\Url::to(['site/signup']) ?>"
                       class="btn btn-outline-light px-4">Registar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="bg-white text-secondary py-4 mt-5 w-100 border border-primary border-2">
    <div class="container d-flex justify-content-between">
        <span>LearnQuiz 2025</span>
        <small><span>Instituto Politécnico Leiria</span></small>
        <div>
            <a href="#" class="text-secondary me-5">Contactos</a>
        </div>
    </div>
</footer>

<?php

/** @var yii\web\View $this */
use yii\helpers\Html;

$this->title = 'LearnQuiz';
$this->registerCssFile("css/index.css");
?>
<div class="main-card">
    <div class="main-index">
        <h2 class="text-primary">
            Bem-vindo ao <span class="text-secondary"><?= Html::encode($this->title)?>
                <?php if (!Yii::$app->user->isGuest):?></span>
            <?= Html::encode(Yii::$app->user->identity->username) ?>!
            <?php endif; ?>
        </h2>
    </div>

    <div class="quiz-container card bg-body text-light mt-5 p-4 mx-auto w-100">
        <h3 class="mb-4 text-primary text-start">Jogo Rápido</h3>

        <div class="d-flex flex-wrap gap-4">

            <div class="card quiz-card text-dark">
                <img src="images/math.jpg" class="card-img-top" alt="Matemática">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Matemática Básica</h5>
                    <p class="card-text text-secondary">Tema: Números e Operações</p>
                    <a href="#" class="btn btn-primary w-100">Jogar</a>
                </div>
            </div>

            <div class="card quiz-card text-dark">
                <img src="images/science.jpg" class="card-img-top" alt="Ciências">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Ciências Naturais</h5>
                    <p class="card-text text-secondary">Tema: Ecossistemas</p>
                    <a href="#" class="btn btn-primary w-100">Jogar</a>
                </div>
            </div>

            <div class="card quiz-card text-dark">
                <img src="images/history.jpg" class="card-img-top" alt="História">
                <div class="card-body">
                    <h5 class="card-title fw-bold">História Mundial</h5>
                    <p class="card-text text-secondary">Tema: Guerras e Revoluções</p>
                    <a href="#" class="btn btn-primary w-100">Jogar</a>
                </div>
            </div>

            <?php if (Yii::$app->user->isGuest): ?>
                <div class="card quiz-card create-card text-center d-flex align-items-center justify-content-center"
                     data-bs-toggle="modal" data-bs-target="#loginModal" style="cursor:pointer;">
                    <div>
                        <div class="plus-icon">+</div>
                        <h5 class="text-secondary mt-2">Cria o teu Jogo</h5>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= yii\helpers\Url::to(['jogo/create']) ?>" class="create-link text-decoration-none">
                    <div class="card quiz-card create-card text-center d-flex align-items-center justify-content-center">
                        <div>
                            <div class="plus-icon">+</div>
                            <h5 class="text-secondary mt-2">Cria o teu jogo</h5>
                        </div>
                    </div>
                </a>
            <?php endif; ?>

        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light border-secondary rounded-3">

                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-primary" id="loginRequiredModalLabel">Acesso necessário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body text-center">
                    Para criares um quiz, precisas de estar autenticado.<br>
                    Inicia sessão ou cria uma conta para continuar.
                </div>

                <div class="modal-footer border-secondary d-flex justify-content-center">
                    <a href="<?= yii\helpers\Url::to(['site/login']) ?>" class="btn btn-primary me-2 px-4">Login</a>
                    <a href="<?= yii\helpers\Url::to(['site/signup']) ?>" class="btn btn-outline-light px-4">Registar</a>
                </div>
            </div>
        </div>
    </div>
</div>


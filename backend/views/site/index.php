<?php
use yii\helpers\Html;
use common\models\Jogo;
use common\models\User;

$this->title = 'Painel Administrativo';

// Contadores
$totalQuizzes = Jogo::find()->count();
$totalPublicQuizzes = Jogo::find()->where(['IsPublic' => 1])->count();
$totalPrivateQuizzes = Jogo::find()->where(['IsPublic' => 0])->count();
$totalUsers = User::find()->count();

// Últimos 5 quizzes
$recentQuizzes = Jogo::find()->orderBy(['datacriacao' => SORT_DESC])->limit(5)->all();
?>

<div class="container mt-4">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row g-4 mb-4">

        <!-- Total Quizzes -->
        <div class="col-md-4">
            <div class="card bg-primary text-dark">
                <div class="card-body">
                    <h5 class="card-title">Quizzes</h5>
                    <p class="card-text fs-2"><?= $totalQuizzes ?></p>
                </div>
            </div>
        </div>

        <!-- Quizzes Públicos -->
        <div class="col-md-4">
            <div class="card bg-success text-dark">
                <div class="card-body">
                    <h5 class="card-title">Quizzes Públicos</h5>
                    <p class="card-text fs-2"><?= $totalPublicQuizzes ?></p>
                </div>
            </div>
        </div>

        <!-- Quizzes Privados -->
        <div class="col-md-4">
            <div class="card bg-blue text-dark">
                <div class="card-body">
                    <h5 class="card-title">Quizzes Privados</h5>
                    <p class="card-text fs-2"><?= $totalPrivateQuizzes ?></p>
                </div>
            </div>
        </div>

        <!-- Total Utilizadores -->
        <div class="col-md-4 mt-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Utilizadores</h5>
                    <p class="card-text fs-2"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Últimos 5 Quizzes em Cards -->
    <h3>Últimos Quizzes Criados</h3>
    <div class="row g-4 mt-2">
        <?php foreach ($recentQuizzes as $quiz): ?>
            <div class="col-md-4">
                <div class="card border-primary shadow-sm text-dark">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($quiz->titulo) ?></h5>
                        <p class="card-text">
                            <strong>Autor:</strong> <?= $quiz->autor ? Html::encode($quiz->autor->username) : 'Sem autor' ?><br>
                            <strong>Data de Criação:</strong> <?= Yii::$app->formatter->asDatetime($quiz->datacriacao, 'php:d/m/Y H:i') ?><br>
                            <strong>Status:</strong> <?= $quiz->IsPublic ? 'Público' : 'Privado' ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
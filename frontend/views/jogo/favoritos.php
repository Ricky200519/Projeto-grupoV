<?php

use yii\helpers\Html;

/** @var common\models\Jogo[] $favoritos */
/** @var int $totalJogos */
/** @var int $totalFavoritos */
$this->title = 'Favoritos';
?>

<div class="main-card bg-white mt-4 border border-primary border-2 p-4">

    <h2 class="text-primary mb-3">Os meus favoritos</h2>

    <div class="d-flex gap-3 mb-4">
        <span class="badge bg-primary p-3">
            Total de jogos: <?= $totalJogos ?>
        </span>
        <span class="badge bg-secondary p-3">
            Jogos favoritos: <?= $totalFavoritos ?>
        </span>
    </div>

    <?php if (!empty($favoritos)): ?>
        <div class="row">
            <?php foreach ($favoritos as $jogo): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border border-secondary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?= Html::encode($jogo->titulo) ?>
                            </h5>
                            <p class="card-text">
                                <?= Html::encode($jogo->descricao ?: 'Sem descrição') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center gap-2">
                            <div class="d-flex gap-2">
                                <?= Html::a('Jogar', ['jogo/apresentacao', 'jogo_id' => $jogo->id], [
                                        'class' => 'btn btn-success fw-bold'
                                ]) ?>

                                <?= Html::a('Ver Estatísticas', ['jogo/stats', 'jogo_id' => $jogo->id], [
                                        'class' => 'btn btn-primary fw-bold'
                                ]) ?>
                            </div>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h3 class="text-secondary">Ainda não adicionaste nenhum jogo aos favoritos.</h3><br>
    <div class="d-flex gap-3"><h4>Experimenta jogar um jogo aqui:</h4><?= Html::a('Ver jogos', ['jogo/index'], ['class' => 'btn btn-primary']) ?></div>
    <?php endif; ?>

</div>

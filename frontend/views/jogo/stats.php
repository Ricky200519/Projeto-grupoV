<?php

use common\models\Favoritos;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var common\models\Jogo $jogo */

$this->title = "Estatísticas - " . Html::encode($jogo->titulo);
$userId = Yii::$app->user->id;

$tentativas = $jogo->getTentativas(Yii::$app->user->id);
$melhorPontuacao = $jogo->getMelhorPontuacao(Yii::$app->user->id);
$ranking = $jogo->getRanking();
$top3 = $jogo->getTop3();
$posicao = $jogo->getPosicaoJogador($userId);
$totalRatings = $jogo->getTotalRatings();
$mediaRatings = $jogo->getMediaRating();
$corTrofeu = $jogo->getCorMedalha($posicao);
$isFavorito = Favoritos::isFavorito($userId, $jogo->id);


$dataProvider = new ArrayDataProvider([
        'allModels' => $ranking,
        'pagination' => ['pageSize' => 1000],
]);
?>

<div class="main-card bg-white mt-4 border border-primary border-2">
    <div class="card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">

        <?= Html::a('← <span class="text-primary">Voltar para os Meus Jogos</span>', ['jogo/index'], ['class' => 'mb-3 text-secondary d-block']) ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-primary"><?= Html::encode($jogo->titulo) ?></h2>
            <?= Html::a('Jogar Agora', ['jogo/apresentacao', 'jogo_id' => $jogo->id], ['class' => 'btn btn-primary btn-lg fw-bold']) ?>
        </div>

        <p class="text-secondary mb-4"><?= Html::encode($jogo->descricao ?: "Sem descrição disponível.") ?></p>

        <div class="mb-3">
            <?php if ($isFavorito): ?>
                <?= Html::a('<i class="fas fa-star"></i> Remover Jogo dos favoritos', ['jogo/remover-favorito', 'jogo_id' => $jogo->id], ['class' => 'btn btn-sm btn-warning']) ?>
            <?php else: ?>
                <?= Html::a('<i class="far fa-star"></i> Adicionar Jogo aos favoritos', ['jogo/adicionar-favorito', 'jogo_id' => $jogo->id], ['class' => 'btn btn-sm btn-warning']) ?>
            <?php endif; ?>
        </div>


        <div class="mb-4">
            <h5 class="fw-bold mt-4 mb-3 text-secondary">Avaliações do Jogo</h5>
            <h6 class="text-secondary mb-1">
                Avaliação média:
                <strong><?= $mediaRatings ? number_format($mediaRatings, 1) : '—' ?></strong>
            </h6>
            <h6 class="text-secondary">
                Número de avaliações:
                <strong><?= $totalRatings ?></strong>
            </h6>
        </div>

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card shadow border border-primary border-2">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Vezes Jogadas</h5>
                        <h2 class="fw-bold text-primary"><?= $tentativas ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow border border-primary border-2">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Melhor Pontuação</h5>
                        <h2 class="fw-bold text-success"><?= $melhorPontuacao ?: '—' ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow border border-primary border-2">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Posição no Ranking</h5>
                        <h2 class="fw-bold <?= $posicao ? 'text-black' : 'text-muted' ?>">
                            <?php if ($posicao): ?>
                                <i class="fas fa-trophy me-1" style="color: <?= $corTrofeu ?>"></i>
                                <?= $posicao ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <h4 class="fw-bold mt-4 mb-3 text-secondary">
            <span class="text-warning"><i class="fas fa-trophy"></i></span>
            Top 3 Jogadores
        </h4>

        <?php if ($top3): ?>
            <div class="row mb-4">
                <?php foreach ($top3 as $i => $linha): ?>
                    <?php $corMedalha = $jogo->getCorMedalha($i + 1); ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm text-center p-3" style="border: 3px solid <?= $corMedalha ?>;">
                            <h5><strong class="text-primary"><?= ($i + 1) ?>º lugar</strong></h5>

                            <h5 class="text-secondary fw-bold">
                                <?php if ($i === 0): ?>
                                    <span class="text-warning"><i class="fas fa-crown"></i></span>
                                <?php endif; ?>
                                <?= Html::encode($linha['username']) ?>
                            </h5>

                            <p class="m-0 text-primary fw-bold"><?= $linha['pontuacao'] ?> pontos</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-secondary">Ainda não existem pontuações.</p>
        <?php endif; ?>

        <h4 class="fw-bold mt-4">Ranking Geral</h4>

        <table class="table table-hover mt-3 shadow-sm">
            <thead class="table-primary">
            <tr>
                <th>Nº</th>
                <th>Jogador</th>
                <th>Pontuação</th>
            </tr>
            </thead>

            <tbody class="text-primary table-light">
            <?php foreach ($dataProvider->models as $index => $linha): ?>
                <tr>
                    <td class="fw-bold"><?= $index + 1 ?></td>
                    <td class="text-secondary">
                        <?= Html::encode($linha->user->username) ?>
                        <?php if ($linha->user_id == $userId): ?>
                            <span class="text-primary">(tu)</span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-primary"><?= $linha->pontuacao ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

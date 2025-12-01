<?php

use common\models\Favoritos;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\Tentativa $tentativa */
/** @var array $pontuacao */
/** @var int|null $posicao */
/** @var common\models\Jogador[] $ranking */
/** @var common\models\Rating|null $ratingExistente */
$dados = $tentativa->calcularPontuacao();
$posicao = $tentativa->posicaoJogadorAtual();
$userId = Yii::$app->user->id;
$jogo= $tentativa->jogo;
$isFavorito = Favoritos::isFavorito($userId, $jogo->id);


?>

<div class="card bg-body p-4 mt-5 shadow-sm rounded" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-primary text-center mb-4" style="font-size: 2.2rem;">
        Parabéns, Jogo concluído!<br>
        <?= Html::encode($tentativa->jogo->titulo) ?>
        <?php if ($posicao): ?>
            <br>
            <span class="badge text-secondary mt-2" style="font-size: 1rem;">
                Estás no TOP <?= $posicao ?> deste jogo!
            </span>
        <?php endif; ?>
    </h2>

    <p class="text-center mb-3">
        <strong>Data da tentativa:</strong> <?= Yii::$app->formatter->asDatetime($tentativa->datahora) ?>
    </p>
    <div class="text-center mb-3">
        <?php if ($isFavorito): ?>
            <?= Html::a('<i class="fas fa-star"></i> Remover Jogo dos favoritos', ['jogo/remover-favorito', 'jogo_id' => $jogo->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?php else: ?>
            <?= Html::a('<i class="far fa-star"></i> Adicionar Jogo aos favoritos', ['jogo/adicionar-favorito', 'jogo_id' => $jogo->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?php endif; ?>
    </div>

    <div class="text-center mb-4">
        <span class="badge bg-primary" style="font-size: 2rem; padding: 1rem 1.5rem;">
            <?= $pontuacao['totalPontos'] ?> / <?= $pontuacao['totalPontosMax'] ?> Pontos
        </span>
    </div>

    <div class="mb-4">
        <h5 class="mb-2"><strong>Respostas certas: </strong><?= $pontuacao['acertos'] ?> / <?= $pontuacao['totalPerguntas'] ?></h5>
        <div class="progress" style="height: 1.5rem; border-radius: 0.75rem;">
            <div id="progressBar" class="progress-bar bg-primary" role="progressbar"
                 style="width: 0; transition: width 1s;">0%</div>
        </div>
    </div>

    <div class="card mt-4 p-3 shadow-sm">
        <h4 class="text-center mb-3">Avalia este jogo</h4>
        <div class="text-center">
            <?php if ($ratingExistente): ?>
                <p class="text-warning fas fa-star"><span class="text-primary">Classificaste este jogo com
                    <strong><?= Html::encode($ratingExistente->estrelas) ?> estrela(s)</strong></span>
                </p>
            <?php else: ?>
                <form id="ratingForm" method="post" action="<?= Url::to(['jogo/rating']) ?>">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <input type="hidden" name="jogo_id" value="<?= $tentativa->jogo_id ?>">
                    <input type="hidden" name="estrelas" id="ratingValue">
                </form>
                <div id="ratingStars" style="font-size: 2rem; cursor:pointer;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star" data-value="<?= $i ?>">☆</span>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="accordion mb-4" id="respostasAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRespostas">
                <button class="accordion-button fas fa-check" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRespostas" aria-expanded="true" aria-controls="collapseRespostas">
                    &nbsp;As tuas respostas
                </button>
            </h2>
            <div id="collapseRespostas" class="accordion-collapse collapse show" aria-labelledby="headingRespostas">
                <div class="accordion-body">
                    <ul class="list-group" id="respostasList">
                        <?php foreach ($tentativa->opcaoescolhidas as $op): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center
                                <?= $op->resposta->correta ? 'list-group-item-success' : 'list-group-item-danger' ?>
                                rounded mb-2 shadow-sm resposta-item"
                                style="opacity: 0; transform: translateY(20px); transition: all 0.5s;">
                                <div>
                                    <strong>Pergunta:</strong> <?= Html::encode($op->resposta->pergunta->texto) ?><br>
                                    <strong>Resposta:</strong> <?= Html::encode($op->resposta->texto) ?>
                                    <?php if (!$op->resposta->correta): ?>
                                        <br>
                                        <strong>Correta:</strong> <?= Html::encode($op->resposta->pergunta->respostaCorreta->texto) ?>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 1.5rem;">
                                    <?php if ($op->resposta->correta): ?>
                                        <i class="fa fa-check-circle text-primary"></i>
                                    <?php else: ?>
                                        <i class="fa fa-times-circle text-danger"></i>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion mb-4" id="rankingAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRanking">
                <button class="accordion-button collapsed fas fa-trophy text-warning" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRanking" aria-expanded="false" aria-controls="collapseRanking">
                    <span class="text-primary">&nbsp;Ver Ranking do Jogo</span>
                </button>
            </h2>
            <div id="collapseRanking" class="accordion-collapse collapse" aria-labelledby="headingRanking"
                 data-bs-parent="#rankingAccordion">
                <div class="accordion-body">
                    <ol class="list-group">
                        <?php foreach ($ranking as $idx => $j): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <?= ($idx + 1) ?>º — <?= Html::encode($j->user->username) ?>
                                    <?php if ($j->user_id == Yii::$app->user->id): ?>
                                        <strong class="text-success">(Tu)</strong>
                                    <?php endif; ?>
                                </span>
                                <span class="badge bg-primary"><?= $j->pontuacao ?> pts</span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <?= Html::a('Voltar aos quizzes', ['jogo/index'], ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.resposta-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = 1;
                item.style.transform = 'translateY(0)';
            }, index * 300);
        });

        const progressBar = document.getElementById('progressBar');
        let width = 0;
        const target = <?= $pontuacao['percentagem'] ?>;
        const interval = setInterval(() => {
            if (width >= target) clearInterval(interval);
            else {
                width++;
                progressBar.style.width = width + '%';
                progressBar.textContent = width + '%';
            }
        }, 20);

        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function () {
                const value = this.dataset.value;
                document.getElementById('ratingValue').value = value;
                document.querySelectorAll('.star').forEach(s => s.textContent = (s.dataset.value <= value ? '★' : '☆'));
                document.getElementById('ratingForm').submit();
            });
        });
    });
</script>

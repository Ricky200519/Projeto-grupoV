<?php

use common\models\Favoritos;
use yii\helpers\Html;

$this->title = 'Os Meus Jogos';
$this->registerCssFile("@web/css/quiz-page.css");
$this->registerJsFile("@web/js/jogo-index.js", ['depends' => [\yii\web\JqueryAsset::class]]);

$userId = Yii::$app->user->id;

?>
<div class="main-card">
    <div class="quiz-container card bg-body text-light mt-5 p-5 mx-auto w-100" style="max-width: 1200px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary mb-0"><?= Html::encode($this->title) ?></h2>
            <?= Html::a('+ Criar Novo Jogo', ['create'], ['class' => 'btn btn-primary fw-bold']) ?>
        </div>

        <div class="accordion mb-4" id="accordionMeus">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMeus">
                    <button class="accordion-button bg-primary text-white" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMeus"
                            aria-expanded="true" aria-controls="collapseMeus">
                        <i class="fas fa-chevron-right me-2 arrow"></i> Os Meus Jogos
                    </button>
                </h2>
                <div id="collapseMeus" class="accordion-collapse collapse show" aria-labelledby="headingMeus"
                     data-bs-parent="#accordionMeus">
                    <div class="accordion-body">
                        <?php if (!empty($meusJogos)): ?>
                            <div class="d-flex flex-wrap gap-4">
                                <?php foreach ($meusJogos as $jogo): ?>
                                    <?php
                                    $tentativas = $jogo->getTentativas($userId);
                                    $posicao = $jogo->getPosicaoJogador($userId);
                                    $cor = $jogo->getCorMedalha($posicao);
                                    $media = $jogo->mediaRating ? ceil($jogo->mediaRating) : 0;
                                    $totalRatings = $jogo->totalRatings;
                                    $isFavorito = \common\models\Favoritos::isFavorito($userId, $jogo->id);
                                    ?>
                                    <a href="<?= \yii\helpers\Url::to(['jogo/view', 'id' => $jogo->id]) ?>"
                                       class="text-decoration-none">
                                        <div class="card quiz-card text-dark" style="width: 18rem;">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h5 class="card-title fw-bold text-secondary mb-0">
                                                        <?= Html::encode($jogo->titulo) ?>
                                                    </h5>
                                                    <?php if ($isFavorito): ?>
                                                        <i class="fas fa-star text-warning" style="font-size:18px;"></i>
                                                    <?php endif; ?>
                                                    <?php if ($posicao && $posicao <= 3): ?>
                                                        <span class="fw-bold fas fa-trophy" style="color: <?= $cor ?>;">Top <?= $posicao ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="card-text text-secondary mb-1">
                                                    <?= Html::encode($jogo->descricao ?: 'Sem descrição') ?>
                                                </p>
                                                <?php if ($tentativas > 0): ?>
                                                    <p class="text-secondary fw-bold mb-1">
                                                        Jogaste <?= $tentativas ?> vezes
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-secondary mb-1">Ainda não jogaste este jogo</p>
                                                <?php endif; ?>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Criado
                                                        em: <?= Yii::$app->formatter->asDate($jogo->datacriacao, 'php:d/m/Y') ?>
                                                    </small>
                                                </p>
                                                <?php if ($totalRatings > 0): ?>
                                                    <p class="text-warning mb-1 fas fa-star">
                                                        <?= $media ?> (<?= $totalRatings ?> avaliações)
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-1">Sem avaliações ainda</p>
                                                <?php endif; ?>
                                                <div class="d-flex flex-column gap-2">
                                                    <?= Html::a('Criar Perguntas', ['pergunta/create', 'jogo_id' => $jogo->id], ['class' => 'btn btn-primary btn-sm fw-bold']) ?>
                                                    <?= Html::a('Editar', ['update', 'id' => $jogo->id], ['class' => 'btn btn-secondary btn-sm fw-bold']) ?>
                                                    <?= Html::a('Jogar', ['jogo/apresentacao', 'jogo_id' => $jogo->id], ['class' => 'btn btn-success btn-sm fw-bold']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-secondary">Ainda não criaste nenhum quiz.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionPublicos">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPublicos">
                    <button class="accordion-button collapsed bg-secondary text-white" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapsePublicos"
                            aria-expanded="false" aria-controls="collapsePublicos">
                        <i class="fas fa-chevron-right me-2 arrow"></i> Jogos Públicos
                    </button>
                </h2>
                <div id="collapsePublicos" class="accordion-collapse collapse show" aria-labelledby="headingPublicos"
                     data-bs-parent="#accordionPublicos">
                    <div class="accordion-body">
                        <?php if (!empty($publicos)): ?>
                            <div class="d-flex justify-content-start mb-3 gap-2">
                                <span class="text-secondary fw-bold align-self-center">Ordenar por:</span>
                                <?= Html::a('Nome', ['jogo/index', 'ordenar' => 'nome'], [
                                        'class' => Yii::$app->request->get('ordenar') == 'nome' ? 'btn btn-primary btn-sm' : 'btn btn-outline-primary btn-sm'
                                ]) ?>
                                <?= Html::a('Vezes jogadas', ['jogo/index', 'ordenar' => 'tentativas'], [
                                        'class' => Yii::$app->request->get('ordenar') == 'tentativas' ? 'btn btn-primary btn-sm' : 'btn btn-outline-primary btn-sm'
                                ]) ?>
                            </div>
                            <div class="d-flex flex-wrap gap-4 align-items-start">
                                <?php foreach ($publicos as $jogo): ?>
                                    <?php
                                    $tentativas = $jogo->getTentativas($userId);
                                    $posicao = $jogo->getPosicaoJogador($userId);
                                    $cor = $jogo->getCorMedalha($posicao);
                                    $media = $jogo->mediaRating ? ceil($jogo->mediaRating) : 0;
                                    $totalRatings = $jogo->totalRatings;
                                    $isFavorito = \common\models\Favoritos::isFavorito($userId, $jogo->id);
                                    ?>
                                    <a href="<?= \yii\helpers\Url::to(['jogo/stats', 'jogo_id' => $jogo->id]) ?>"
                                       class="text-decoration-none" style="color: inherit;">
                                        <div class="card quiz-card text-dark" style="width: 18rem; cursor: pointer;">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h5 class="card-title fw-bold text-secondary mb-0">
                                                        <?= Html::encode($jogo->titulo) ?>
                                                    </h5>
                                                    <?php if ($isFavorito): ?>
                                                        <i class="fas fa-star text-warning" style="font-size:18px;"></i>
                                                    <?php endif; ?>
                                                    <?php if ($posicao && $posicao <= 3): ?>
                                                        <span class="fw-bold fas fa-trophy" style="color: <?= $cor ?>;">Top <?= $posicao ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="card-text text-secondary mb-1">
                                                    <?= Html::encode($jogo->descricao ?: 'Sem descrição') ?>
                                                </p>
                                                <?php if ($tentativas > 0): ?>
                                                    <p class="text-black fw-bold mb-1">
                                                        Jogaste <?= $tentativas ?> vezes
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-secondary mb-1">Ainda não jogaste este jogo</p>
                                                <?php endif; ?>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Criado
                                                        por: <?= Html::encode($jogo->autor->username ?? 'Desconhecido') ?>
                                                    </small>
                                                </p>
                                                <?php if ($totalRatings > 0): ?>
                                                    <p class="text-warning mb-1 fas fa-star">
                                                        <?= $media ?> (<?= $totalRatings ?> avaliações)
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-1">Sem avaliações ainda</p>
                                                <?php endif; ?>
                                                <div class="d-flex flex-column gap-2">
                                                    <?= Html::a('Jogar', ['jogo/apresentacao', 'jogo_id' => $jogo->id], ['class' => 'btn btn-success btn-sm fw-bold']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-secondary">Ainda não existem quizzes públicos disponíveis.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
use yii\helpers\Html;

$this->title = 'Os Meus Jogos';
$this->registerCssFile("@web/css/quiz-page.css");
$this->registerJsFile("@web/js/jogo-index.js", ['depends' => [\yii\web\JqueryAsset::class]]);
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

                <div id="collapseMeus" class="accordion-collapse collapse show" aria-labelledby="headingMeus" data-bs-parent="#accordionMeus">
                    <div class="accordion-body">
                        <?php if (!empty($meusJogos)): ?>
                            <div class="d-flex flex-wrap gap-4">
                                <?php foreach ($meusJogos as $jogo): ?>
                                <a href="<?= \yii\helpers\Url::to(['jogo/view', 'id' => $jogo->id]) ?>" class="text-decoration-none">
                                    <div class="card quiz-card text-dark" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold text-secondary"><?= Html::encode($jogo->titulo) ?></h5>
                                                <p class="card-text text-secondary mb-1">
                                                    <?= Html::encode($jogo->descricao ?: 'Sem descrição') ?>
                                                </p>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Criado em: <?= Yii::$app->formatter->asDate($jogo->datacriacao, 'php:d/m/Y') ?>
                                                    </small>
                                                </p>

                                                <div class="d-flex flex-column gap-2">
                                                    <?= Html::a('Criar Perguntas', ['pergunta/create', 'jogo_id' => $jogo->id], ['class' => 'btn btn-primary btn-sm fw-bold']) ?>
                                                    <?= Html::a('Editar', ['update', 'id' => $jogo->id], ['class' => 'btn btn-primary btn-sm fw-bold']) ?>
                                                    <?= Html::beginForm(['delete', 'id' => $jogo->id], 'post', ['style' => 'display:inline']) ?>
                                                    <?= Html::submitButton('Eliminar', [
                                                            'class' => 'btn btn-danger btn-sm fw-bold w-100',
                                                            'data' => ['confirm' => 'Tens a certeza que queres eliminar este quiz?'],
                                                    ]) ?>
                                                    <?= Html::endForm() ?>
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

                <div id="collapsePublicos" class="accordion-collapse collapse show" aria-labelledby="headingPublicos" data-bs-parent="#accordionPublicos">
                    <div class="accordion-body">
                        <?php if (!empty($publicos)): ?>
                            <div class="d-flex flex-wrap gap-4">
                                <?php foreach ($publicos as $jogo): ?>
                                    <div class="card quiz-card text-dark" style="width: 18rem;">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold"><?= Html::encode($jogo->titulo) ?></h5>
                                            <p class="card-text text-secondary mb-1">
                                                <?= Html::encode($jogo->descricao ?: 'Sem descrição') ?>
                                            </p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Criado por: <?= Html::encode($jogo->autor ? $jogo->autor->username : 'Desconhecido') ?>
                                                 </small>
                                            </p>
                                            <?= Html::a('Jogar', ['site/index'], ['class' => 'btn btn-success btn-sm w-100 fw-bold']) ?>
                                        </div>
                                    </div>
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

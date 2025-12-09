<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Jogo $model */
/** @var common\models\Pergunta[] $perguntas */

$this->title = 'Detalhes do Quiz: ' . $model->titulo;
$this->registerCssFile("@web/css/quiz-page.css");
?>
<div class="main-card bg-white mt-4 border border-primary border-2">
    <div class="quiz-details card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">

        <?= Html::a('← <span class="text-primary">Voltar para os Meus Jogos</span>', ['jogo/index'], ['class' => 'mb-3 text-secondary d-block']) ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0"><?= Html::encode($model->titulo) ?></h2>
            <?= Html::a('Ver Estatísticas', ['jogo/stats', 'jogo_id' => $model->id], [
                    'class' => 'btn btn-primary fw-bold'
            ]) ?>
        </div>

        <p class="mb-2"><?= Html::encode($model->descricao ?: 'Sem descrição') ?></p>
        <p class="text-muted mb-3">
            Criado em: <?= Yii::$app->formatter->asDate($model->datacriacao, 'php:d/m/Y') ?>
            | Autor: <?= Html::encode($model->autor ? $model->autor->username : 'Desconhecido') ?>
        </p>
        <div>
            <?= Html::a('Adicionar Pergunta', ['pergunta/create', 'jogo_id' => $model->id], [
                    'class' => 'btn btn-primary mb-3',
            ]) ?></div>

        <h3 class="text-secondary">Perguntas</h3>
        <?php if (!empty($perguntas)): ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Nº</th>
                    <th>Pergunta</th>
                    <th>Tempo Limite</th>
                    <th>Pontos</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($perguntas as $i => $pergunta): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($pergunta->texto) ?></td>
                        <td><?= $pergunta->tempolimite ?> seg</td>
                        <td><?= $pergunta->pontosvalor ?></td>
                        <td class="d-flex flex-wrap gap-2">
                            <?= Html::a('Editar', ['pergunta/update', 'id' => $pergunta->id, 'jogo_id' => $pergunta->jogo_id], [
                                    'class' => 'btn btn-sm btn-secondary'
                            ]) ?>
                            <?= Html::a('Ver opções de Resposta', ['resposta/view', 'pergunta_id' => $pergunta->id], ['class' => 'btn btn-sm btn-primary']) ?>
                            <?= Html::a('Eliminar', ['pergunta/delete', 'id' => $pergunta->id], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Tens a certeza que queres eliminar esta pergunta?']
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-secondary">Ainda não existem perguntas para este quiz.</p>
        <?php endif; ?>

        <div class="mt-4">
            <?= Html::beginForm(['jogo/delete', 'id' => $model->id], 'post', ['style' => 'display:inline']) ?>
            <?= Html::submitButton('Eliminar Quiz', [
                    'class' => 'btn btn-danger',
                    'data' => [
                            'confirm' => 'Tens a certeza que queres eliminar este quiz?',
                    ],
            ]) ?>
            <?= Html::endForm() ?>
        </div>

    </div>
</div>


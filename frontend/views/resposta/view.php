<?php
use yii\helpers\Html;
use common\models\Resposta;

/** @var common\models\Pergunta $pergunta */
/** @var Resposta[] $respostas */

$this->title = 'Respostas da Pergunta: ' . $pergunta->texto;

$respostasCount = count($respostas);
$respostasCorretas = Resposta::find()->where(['pergunta_id' => $pergunta->id, 'correta' => 1])->all();
$totalCorretas = count($respostasCorretas);

?>
<div class="main-card">
    <div class="resposta-details card p-4 mb-4">
        <?= Html::a('← Voltar ao jogo', ['resposta/back-to-game', 'pergunta_id' => $pergunta->id], [
                'class' => 'text-primary mb-3 d-inline-block'
        ]) ?>

        <h2 class="mb-3"><?= Html::encode($pergunta->texto) ?></h2>

        <?php if ($respostasCount > 0): ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Resposta</th>
                    <th>Correta</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($respostas as $i => $resposta): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($resposta->texto) ?></td>
                        <td><?= $resposta->correta ? 'Sim' : 'Não' ?></td>
                        <td class="d-flex flex-wrap gap-2">
                            <?= Html::a('Editar', ['resposta/update', 'id' => $resposta->id], ['class' => 'btn btn-sm btn-secondary']) ?>
                            <?= Html::a('Eliminar', ['resposta/delete', 'id' => $resposta->id], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Tens a certeza que queres eliminar esta resposta?'
                                    ]
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-secondary">Ainda não existem respostas para esta pergunta.</p>
        <?php endif; ?>

        <?= Html::a('Adicionar Resposta', ['resposta/create', 'pergunta_id' => $pergunta->id], [
                'class' => 'btn btn-primary mt-3' . ($respostasCount >= 4 ? ' disabled' : ''),
                'title' => $respostasCount >= 4 ? 'Já existem 4 respostas' : ''
        ]) ?>
    </div>
</div>

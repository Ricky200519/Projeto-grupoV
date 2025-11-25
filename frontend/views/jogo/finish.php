<?php
use yii\helpers\Html;

/** @var common\models\Tentativa $tentativa */
/** @var int $score */

$this->title = 'Resultados do Quiz';

$opcoes = $tentativa->opcaoescolhidas;
?>

<div class="card bg-body p-4 mt-4" style="margin: 0 auto;">

    <h2 class="text-primary mb-3 text-center">
        Quiz concluído: <?= Html::encode($tentativa->jogo->titulo) ?>
    </h2>

    <p class="text-center"><strong>Data da tentativa:</strong> <?= Yii::$app->formatter->asDatetime($tentativa->datahora) ?></p>
    <p class="text-center"><strong>Pontuação total:</strong> <?= $score ?></p>

    <h4 class="mt-4">As tuas respostas:</h4>

    <ul class="list-group mt-2">
        <?php foreach ($opcoes as $op): ?>
            <li class="list-group-item <?= $op->resposta->correta ? 'list-group-item-success' : 'list-group-item-danger' ?>">
                <strong>Pergunta:</strong> <?= Html::encode($op->resposta->pergunta->texto) ?><br>
                <strong>Sua resposta:</strong> <?= Html::encode($op->resposta->texto) ?>
                <?= $op->resposta->correta ? '(Correta)' : '(Errada)' ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?= Html::a('Voltar aos quizzes', ['jogo/index'], ['class' => 'btn btn-primary mt-3']) ?>
</div>

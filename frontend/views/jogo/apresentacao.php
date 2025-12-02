<?php
use yii\helpers\Html;

/** @var common\models\Jogo $jogo */
/** @var int $totalPerguntas */

$this->title = 'Apresentação do Quiz: ' . $jogo->titulo;
?>

<div class="card bg-body p-4 mt-4" style="max-width: 700px; margin: 0 auto;">
    <h2 class="text-primary mb-3"><?= Html::encode($jogo->titulo) ?></h2>

    <?php if ($jogo->descricao): ?>
        <p><strong>Descrição:</strong> <?= Html::encode($jogo->descricao) ?></p>
    <?php endif; ?>

    <p><strong>Total de perguntas:</strong> <?= $totalPerguntas ?></p>

    <?= Html::a(
            'Começar Quiz',
            ['jogo/start', 'jogo_id' => $jogo->id],
            ['class' => 'btn btn-success mt-3']
    ) ?>
</div>

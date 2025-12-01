<?php
use yii\helpers\Html;

/** @var common\models\Jogo $jogo */
/** @var int $totalPerguntas */

$this->title = 'Apresentação do Quiz: ' . $jogo->titulo;
?>

<div class="card bg-body p-4 mt-4" style="max-width: 700px; margin: 0 auto;">
    <h1 class="display-2 text-primary mb-3 text-center"><?= Html::encode($jogo->titulo) ?></h1>

    <?php if ($jogo->descricao): ?>
        <p><strong>Descrição:</strong> <?= Html::encode($jogo->descricao) ?></p>
    <?php endif; ?>

    <p><strong>Total de perguntas:</strong> <?= $totalPerguntas ?></p>

    <div class="alert alert-info mt-3">
        <h5>Regras do Quiz:</h5>
        <ul>
            <li>Responde ás <strong><?= $totalPerguntas ?></strong> perguntas para completar o jogo.</li>
            <li>Cada resposta correta dá pontos conforme o valor designado por pergunta.</li>
            <li>Não há penalização por respostas erradas.</li>
            <li>Após submeter a resposta és automaticamente direcionado para a próxima pergunta.</li>
        </ul>
    </div>

    <?= Html::a(
            'Começar Quiz',
            ['jogo/start', 'jogo_id' => $jogo->id],
            ['class' => 'btn btn-success mt-3']
    ) ?>
</div>

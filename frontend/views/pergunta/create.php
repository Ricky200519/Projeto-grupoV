<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Pergunta $model */
/** @var int $jogo_id */
/** @var int $nextNumber */

$this->title = 'Adicionar Pergunta nº ' . $nextNumber . ' ao Jogo ' . $jogo_id;
$this->registerCssFile("@web/css/create-quiz.css");
?>
<div class="pergunta-create-container  p-4 bg-light">
    <div class="pergunta-create-card">
        <h2 class="text-primary mb-3"><?= Html::encode($this->title) ?></h2>

        <?= $this->render('_form', [
                'model' => $model,
                'jogo_id' => $jogo_id,
                'nextNumber' => $nextNumber,
                'text' => 'Criar'

        ]) ?>

    </div>
</div>

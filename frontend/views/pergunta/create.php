<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Pergunta $model */
/** @var int $jogo_id */
/** @var int $nextNumber */

$this->title = 'Adicionar Pergunta nº ' . $nextNumber . ' ao Jogo ' . $jogo_id;
$this->registerCssFile("@web/css/create-quiz.css");
?>
<div class="main-card bg-white mt-4 border border-primary border-2">
    <div class="card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">
        <h2 class="text-primary mb-3"><?= Html::encode($this->title) ?></h2>

        <?= $this->render('_form', [
                'model' => $model,
                'jogo_id' => $jogo_id,
                'nextNumber' => $nextNumber,
                'text' => 'Criar'

        ]) ?>

    </div>
</div>

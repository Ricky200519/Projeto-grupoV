<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Jogo $model */

$this->title = 'Criar Novo Jogo';
?>

<div class="main-card bg-white mt-4 border border-primary border-2">
    <div class="card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">
        <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary mb-4">Preenche os detalhes abaixo sobre o teu jogo</p>

        <?= $this->render('_form', [
                'model' => $model,
                'text' => 'Criar',
        ]) ?>
    </div>
</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Jogo $model */

$this->title = 'Criar Novo Jogo';
$this->registerCssFile("@web/css/create-quiz.css");
?>

<div class="createquiz-container bg-light">
    <div class="createquiz-card">
        <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary mb-4">Preenche os detalhes abaixo sobre o teu jogo</p>

        <?= $this->render('_form', [
                'model' => $model,
                'text' => 'Criar',
        ]) ?>
    </div>
</div>

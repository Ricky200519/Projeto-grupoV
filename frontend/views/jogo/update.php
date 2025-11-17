<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Jogo $model */

$this->title = 'Atualizar Jogo ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jogos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
$this->registerCssFile("@web/css/create-quiz.css");
?>
<div class="jogoupdate-container bg-light">
    <div class="jogoupdate-card">
        <h1 class="mt-3 text-primary"><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_form', [
                'model' => $model,
                'text' => 'Gravar',
        ]) ?>
    </div>
</div>

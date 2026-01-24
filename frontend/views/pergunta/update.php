<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Pergunta $model */

$this->title = 'Editar Pergunta ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Perguntas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
$this->registerCssFile('@web/css/create-quiz.css');
?>
<div class="main-card bg-white mt-4 border border-primary border-2">
    <div class="card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">
        <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
                'model' => $model,
                'text' => 'Gravar'
        ]) ?>
    </div>
</div>

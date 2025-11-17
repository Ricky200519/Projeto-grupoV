<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */

$this->title = 'Update Resposta: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Respostas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
    <div class="resposta-create">

        <?= $this->render('_form', [
                'model' => $model,
                'pergunta' => $model->pergunta,
                'jogo_id' => $model->pergunta->jogo_id,
                'isUpdate' => true,
        ]) ?>

    </div>
<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Pergunta $model */

$this->title = 'Editar Pergunta';
$this->registerCssFile('@web/css/create-quiz.css');
?>

<div class="pergupdate-container bg-light p-4">
    <div class="pergupdate-card">
        <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('form', [
            'model' => $model,
            'jogo_id' => $model->jogo_id,  // importante
            'nextNumber' => $model->id,    // apenas para não falhar o _form
            'text' => 'Guardar'
        ]) ?>
    </div>
</div>

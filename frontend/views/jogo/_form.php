<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Jogo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jogo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true])->label($model->getAttributeLabel('titulo'), ['class' => 'text-primary']) ?>
    <?= $form->field($model, 'descricao')->textarea(['rows' => 1])->label($model->getAttributeLabel('descricao'), ['class' => 'text-primary']) ?>
    <?= $form->field($model, 'IsPublic')->checkbox(['class'=>'text-primary'])?>

    <div class="form-group mt-3">
        <?= Html::submitButton($text.' Jogo', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>

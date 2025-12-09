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
    <div class="form-group mt-2 d-flex align-items-center">
        <label for="jogo-publico" class="text-primary me-2 mb-0">
            Jogo público
        </label>
        <?= Html::activeCheckbox($model, 'IsPublic', [
                'label' => false,
                'id' => 'jogo-publico',
                'class' => 'form-check-input'
        ]) ?>
    </div>


    <div class="form-group mt-3">
        <?= Html::submitButton($text.' Jogo', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>

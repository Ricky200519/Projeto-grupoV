<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Pergunta $model */
/** @var int $jogo_id */
/** @var int $nextNumber */
?>

<div class="pergunta-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'jogo_id')->hiddenInput(['value' => $jogo_id ?? $model->jogo_id])->label(false) ?>

    <?= $form->field($model, 'texto')->textInput([
            'maxlength' => true,
            'placeholder' => 'Escreve a pergunta...'
    ])->label('Pergunta', ['class' => 'text-primary']) ?>

    <?= $form->field($model, 'tempolimite')->input('number', [
            'min' => 5,
            'max' => 120,
            'value' => $model->tempolimite ?? 20,
    ])->label('Tempo Limite (segundos)', ['class' => 'text-primary']) ?>

    <?= $form->field($model, 'pontosvalor')->input('number', [
            'min' => 1,
            'max' => 100,
            'value' => $model->pontosvalor ?? 10,
    ])->label('Pontos (Max.100)', ['class' => 'text-primary']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton($text . ' Pergunta', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script>
    document.querySelectorAll('.pergunta-form input[type=text], .pergunta-form input[type=number]').forEach(input => {
        const label = input.previousElementSibling;
        if (label && label.tagName === 'LABEL') {
            label.addEventListener('click', () => {
                input.focus();
                const val = input.value;
                input.value = '';
                input.value = val;
            });
        }
    });
</script>



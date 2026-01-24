<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */
/** @var common\models\Pergunta $pergunta */
/** @var int|null $total */
/** @var bool $isUpdate */

if (!isset($total)) {
    $total = 0;
}
?>
<div class="quiz-container card bg-white mt-2 p-5 mx-auto w-100 border border-primary border-2">
    <?= Html::a('← Voltar', ['resposta/view', 'pergunta_id' => $pergunta->id], ['class' => 'mb-3 text-primary d-block']) ?>
    <h3 class="text-primary mb-2">
        <?= $isUpdate ? 'Editar resposta' : 'Criar resposta Nº ' . (($total ?? 0) + 1) ?>
    </h3>
    <?php if (!$isUpdate): ?>
        <div class="alert alert-info">
            Respostas atuais: <strong><?= $total ?></strong>.
            Máximo de 4 respostas por pergunta. Uma delas deve ser correta.
        </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'texto', [
            'labelOptions' => ['class' => 'text-primary']
    ])->textInput([
            'maxlength' => true,
            'placeholder' => 'Escreve a resposta...',
            'class' => 'form-control form-control-lg',
    ]) ?>

    <?php
    $mustDisableCheckbox = !$isUpdate && $model->correta == 1;
    if (!$isUpdate && $model->correta == 1) {
        $mustDisableCheckbox = true;
    }
    ?>
    <div class="form-check mb-3">
        <?= Html::checkbox(
                'Resposta[correta]',
                $model->correta == 1,
                [
                        'class' => 'form-check-input resposta-correta-checkbox',
                        'label' => '<i class="text-success"></i> Marcar como correta',
                        'encode' => false,
                        'disabled' => $mustDisableCheckbox,
                        'uncheck' => 0,
                ]
        ) ?>
    </div>
    <?= $form->field($model, 'pergunta_id')->hiddenInput()->label(false) ?>
    <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
        <?php if (!$isUpdate): ?>
            <?= Html::submitButton('Criar outra resposta', [
                    'class' => 'btn btn-primary',
                    'name' => 'add-answer',
                    'disabled' => ($total + 1) >= 4,
            ]) ?>
            <?= Html::submitButton('Criar nova pergunta', [
                    'class' => 'btn btn-secondary',
                    'name' => 'new-question',
                    'disabled' => (($total + 1) % 2 !== 0),
            ]) ?>
        <?php endif; ?>
        <?= Html::submitButton($isUpdate ? 'Guardar alterações' : 'Finalizar', [
                'class' => 'btn btn-success',
                'name' => 'finish',
                'disabled' => !$isUpdate && !(($total + 1) == 2 || ($total + 1) == 4),
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
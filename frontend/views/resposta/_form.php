<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */
/** @var common\models\Pergunta $pergunta */
/** @var int $jogo_id */
/** @var int|null $total */
/** @var bool $isUpdate */
?>

<div class="card bg-body p-4 mt-4" style="max-width: 700px; margin: 0 auto;">
    <?= Html::a('← Voltar', ['resposta/view', 'pergunta_id' => $pergunta->id], ['class' => 'mb-3 text-primary d-block']) ?>

    <h3 class="text-primary mb-3">
        <?= $isUpdate ? 'Editar resposta' : 'Criar resposta Nº ' . (isset($total) ? $total + 1 : '') ?>
    </h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'texto')->textInput([
            'maxlength' => true,
            'placeholder' => 'Escreve a resposta...'
    ]) ?>

    <?= $form->field($model, 'correta')->checkbox([
            'label' => 'Resposta correta?',
            'class' => 'resposta-correta-checkbox',
            'uncheck' => 0,
            'checked' => $model->correta == 1,
    ]) ?>

    <?= $form->field($model, 'pergunta_id')->hiddenInput(['value' => $model->pergunta_id])->label(false) ?>

    <div class="d-flex justify-content-between mt-4">
        <?php if (!$isUpdate): ?>
            <?= Html::submitButton('Criar outra resposta', [
                    'class' => 'btn btn-primary',
                    'name' => 'add-answer',
                    'disabled' => isset($total) && $total >= 3,
            ]) ?>

            <?= Html::submitButton('Criar nova pergunta', [
                    'class' => 'btn btn-primary',
                    'name' => 'new-question',
            ]) ?>
        <?php endif; ?>

        <?= Html::submitButton($isUpdate ? 'Guardar alterações' : 'Finalizar', [
                'class' => 'btn btn-success',
                'name' => 'finish',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("
    $('.resposta-correta-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.resposta-correta-checkbox').not(this).prop('checked', false);
        }
    });
");
?>

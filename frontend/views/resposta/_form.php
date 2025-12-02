<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */
/** @var common\models\Pergunta $pergunta */
/** @var int|null $total */
/** @var bool $isUpdate */
?>

<div class="card  bg-body p-4 mt-4 shadow-sm" style="max-width: 700px; margin: 0 auto;">
    <?= Html::a('← Voltar', ['resposta/view', 'pergunta_id' => $pergunta->id], ['class' => 'mb-3 text-primary d-block']) ?>

    <h3 class="text-primary mb-2">
        <?= $isUpdate ? 'Editar resposta' : 'Criar resposta Nº ' . (isset($total) ? $total + 1 : '') ?>
    </h3>

    <?php if (!$isUpdate): ?>
        <div class="alert alert-info">
            Respostas atuais: <strong><?= isset($total) ? $total : 0 ?></strong>.
            Máximo de 4 respostas por pergunta. Uma delas deve ser correta.
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'texto')->textInput([
            'maxlength' => true,
            'placeholder' => 'Escreve a resposta...',
            'class' => 'form-control form-control-lg',
    ]) ?>

    <div class="form-check mb-3">
        <?= Html::activeCheckbox($model, 'correta', [
                'label' => '<i class=" text-success"></i> Marcar como correta',
                'class' => 'form-check-input resposta-correta-checkbox',
                'uncheck' => 0,
                'encode' => false,
        ]) ?>
    </div>

    <?= $form->field($model, 'pergunta_id')->hiddenInput(['value' => $model->pergunta_id])->label(false) ?>

    <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
        <?php if (!$isUpdate): ?>
            <?= Html::submitButton('Criar outra resposta', [
                    'class' => 'btn btn-primary',
                    'name' => 'add-answer',
                    'title' => 'Adiciona mais uma resposta à pergunta',
                    'disabled' => isset($total) && ($total + 1 >= 4),
            ]) ?>

            <?= Html::submitButton('Criar nova pergunta', [
                    'class' => 'btn btn-secondary',
                    'name' => 'new-question',
                    'title' => 'Só podes criar nova pergunta se houver 2 ou 4 respostas',
                    'disabled' => isset($total) && (($total + 1) % 2 !== 0),
            ]) ?>
        <?php endif; ?>

        <?= Html::submitButton($isUpdate ? 'Guardar alterações' : 'Finalizar', [
                'class' => $isUpdate ? 'btn btn-success' : 'btn btn-success',
                'name' => 'finish',
                'title' => 'Finaliza a criação das respostas',
                'disabled' => isset($total) && (($total + 1) % 2 !== 0),
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

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Pergunta $model */
/** @var int $jogo_id */
/** @var int $nextNumber */
/** @var string $text */
?>

<div class="pergunta-form">

    <?php $form = ActiveForm::begin(); ?>




    <?= $form->field($model, 'texto')
        ->textInput(['placeholder' => 'Insira a pergunta'])
        ->label('Pergunta') ?>


    <?= Html::activeHiddenInput($model, 'jogo_id', ['value' => $jogo_id]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton($text, ['class' => 'btn btn-success']) ?>
        <a href="<?= \yii\helpers\Url::to(['quiz/view', 'id' => $jogo_id]) ?>" class="btn btn-secondary">
            Cancelar
        </a>
    </div>

    <?php ActiveForm::end(); ?>

</div>

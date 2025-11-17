<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sessão';
?>

<div class="min-vh-100 d-flex justify-content-center align-items-center" style="background-color: #787878;">

    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%; border: none; border-radius: 10px; background-color: #D1D1D1;">
        <div class="text-center mb-4">
            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold text-primary"><?= Html::encode($this->title) ?></h3>
            <p class="text-secondary">Para aceder ao backend </p>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username', [
            'labelOptions' => ['class' => 'text-primary fw-semibold'],
        ])->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password', [
            'labelOptions' => ['class' => 'text-primary fw-semibold'],
        ])->passwordInput() ?>

        <div class="d-grid mt-3">
            <?= Html::submitButton('Entrar', [
                'class' => 'btn btn-primary text-white fw-semibold py-2',
                'name' => 'login-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

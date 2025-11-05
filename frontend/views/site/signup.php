<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Signup';
$this->registerCssFile('@web/css/signup.css');
?>

<div class="signup-page">
    <div class="signup-card">
        <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary">Please fill out the following fields to sign up:</p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'class' => 'form-control text-primary'
        ])->label($model->getAttributeLabel('username'), ['class' => 'text-primary'])
        ?>

        <?= $form->field($model, 'email')->textInput([
                'class' => 'form-control'
        ])->label($model->getAttributeLabel('email'), ['class' => 'text-primary'])
        ?>

        <?= $form->field($model, 'password')->passwordInput([
                'class' => 'form-control'
        ])->label($model->getAttributeLabel('password'), ['class' => 'text-primary'])
        ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Signup', [
                    'class' => 'btn btn-primary w-100',
                    'name' => 'signup-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

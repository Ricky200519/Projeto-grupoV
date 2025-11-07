<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->registerCssFile('@web/css/login.css', ['depends' => [yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="login-page">
    <div class="login-container">
        <div class="login-box">
            <!-- Cabeçalho com Logo -->
            <div class="login-header text-center mb-4">
                <div class="login-logo">
                    <h1 class="login-title">
                        <i class="fas fa-graduation-cap me-2"></i>
                        LearnQuiz
                    </h1>
                </div>
            </div>

            <!-- Cartão do Formulário -->
            <div class="login-card">
                <div class="login-body">
                    <h4 class="login-subtitle text-center mb-3">Iniciar Sessão</h4>
                    <p class="login-description text-center mb-4">
                        Por favor preencha os seguintes campos para iniciar sessão:
                    </p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableClientValidation' => true,
                        'validateOnBlur' => true,
                        'validateOnChange' => true,
                    ]); ?>

                    <!-- Campo Username -->
                    <div class="form-group mb-4">
                        <?= $form->field($model, 'username', [
                            'options' => ['class' => 'form-group-custom']
                        ])->textInput([
                            'autofocus' => true,
                            'class' => 'form-control login-input',
                            'placeholder' => 'Username'
                        ])->label('Username', ['class' => 'form-label-custom']) ?>
                    </div>

                    <!-- Campo Password -->
                    <div class="form-group mb-4">
                        <?= $form->field($model, 'password', [
                            'options' => ['class' => 'form-group-custom']
                        ])->passwordInput([
                            'class' => 'form-control login-input',
                            'placeholder' => 'Password'
                        ])->label('Password', ['class' => 'form-label-custom']) ?>
                    </div>

                    <!-- Botão Entrar -->
                    <div class="form-group">
                        <?= Html::submitButton('Entrar', [
                            'class' => 'btn btn-primary login-btn w-100',
                            'name' => 'login-button'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

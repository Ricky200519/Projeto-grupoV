<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->registerCssFile('@web/css/login.css');
?>

<div class="login-page">
    <div class="login-card bg-white border border-primary border-2">
        <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary">Preenche com as tuas credenciais para fazer Login</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label($model->getAttributeLabel('username'), ['class' => 'text-primary']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autofocus' => false])->label($model->getAttributeLabel('password'), ['class' => 'text-primary']) ?>
        <?= $form->field($model, 'rememberMe')->checkbox(['autofocus' => false])->label($model->getAttributeLabel('rememberMe'), ['class' => 'text-secondary']) ?>

        <div class="form-group">
            <?= Html::submitButton('Login', [
                    'class' => 'btn btn-primary w-100',
                    'name' => 'login-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

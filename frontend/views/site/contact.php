<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contacta-nos';
$this->registerCssFile('@web/css/contact.css');
$this->registerJsFile('@web/js/contact-scroll.js', ['depends' => \yii\web\JqueryAsset::class]);

?>

<div class="contact-page">
    <div class="contact-card">
        <h1 class="text-primary"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary">
            Se tiver alguma dúvida ou sugestão, não hesite em contactar-nos. Obrigado.
        </p>

        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label($model->getAttributeLabel('Nome'), ['class' => 'text-primary']) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'email')->textInput()->label($model->getAttributeLabel('Email'), ['class' => 'text-primary']) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'subject')->textInput()->label($model->getAttributeLabel('Assunto'), ['class' => 'text-primary']) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'body')->textarea(['rows' => 3])->label($model->getAttributeLabel('Mensagem'), ['class' => 'text-primary']) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
6</div>
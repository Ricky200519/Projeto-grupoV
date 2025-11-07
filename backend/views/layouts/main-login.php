<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\PluginAsset;

AdminLteAsset::register($this);
$this->registerCssFile('@web/css/login.css', ['depends' => [\hail812\adminlte3\assets\AdminLteAsset::class]]);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Html::encode(Yii::$app->name) ?> | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #1e1e1e !important;
            color: #f5f5f5 !important;
            font-family: 'Source Sans Pro', sans-serif;
        }

        /* Centralizar a caixa de login */
        .login-page {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh !important;
            background-color: #1e1e1e !important;
        }

        /* Caixa principal */
        .login-box {
            width: 380px;
            z-index: 10;
        }

        /* Logo */
        .login-logo a {
            color: #00bfff;
            font-weight: bold;
        }

        /* Cartão */
        .card {
            background-color: #2b2b2b !important;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .card-body {
            background-color: #2b2b2b !important;
            color: #f5f5f5 !important;
        }

        /* Botão */
        .btn-primary {
            background-color: #00bfff !important;
            border-color: #00bfff !important;
        }

        .btn-primary:hover {
            background-color: #0099cc !important;
            border-color: #0099cc !important;
        }

        /* Links */
        a {
            color: #00bfff;
        }
        a:hover {
            color: #0099cc;
        }

    </style>
</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>



    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

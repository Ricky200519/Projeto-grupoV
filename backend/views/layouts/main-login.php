<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\PluginAsset;

AdminLteAsset::register($this);
$this->registerCssFile('@web/css/login.css', ['depends' => [\hail812\adminlte3\assets\AdminLteAsset::class]]);
$this->registerCssFile('@web/css/bootstrap.min.css');
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

</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>



    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte\widgets\Alert;

AdminLteAsset::register($this);
$this->registerCssFile('@web/css/main.css', ['depends' => [\hail812\adminlte3\assets\AdminLteAsset::class]]);
$this->registerCssFile("@web/css/bootstrap.min.css");
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link href="../../web/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="../../web/css/bootstrap.min.css"/>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
    <?php $this->beginBody() ?>

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand bg-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars text-secondary"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= Url::to(['/site/index']) ?>" class="nav-link text-white">Página Principal</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (Yii::$app->user->isGuest): ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/site/login']) ?>" class="nav-link">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <?= Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link nav-link logout text-white']
                        )
                        . Html::endForm(); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <aside class="main-sidebar elevation-2 bg-dark" >
            <a href="<?= Url::to(['/site/index']) ?>" class="brand-link">
                <span class="brand-text text-primary">Painel Admin</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">
                            <?= Yii::$app->user->identity->username ?? 'Convidado' ?>
                        </a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">


                        <?php if (Yii::$app->user->can('admin')): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(['/user/index']) ?>" class="nav-link text-primary">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Utilizadores</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('admin')): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(['/quiz/index']) ?>" class="nav-link text-primary">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Gerir Quizzes</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>


        <div class="content-wrapper p-3">
            <?= $content ?>
        </div>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
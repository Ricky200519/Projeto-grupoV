<?php
/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

BootstrapPluginAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?= Yii::getAlias('@web') ?>/icon.png">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <meta content="" name="keywords">
        <meta content="" name="description">
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Roboto:wght@400;500;700;900&display=swap"
              rel="stylesheet">
        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="../../web/css/style.css" rel="stylesheet">
        <link href="../../web/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../web/css/index.css">
        <?= Html::csrfMetaTags() ?>
    </head>
    <body class="d-flex flex-column min-vh-100 bg-light">
    <?php $this->beginBody() ?>
    <header>
        <?php
        $currentAction = Yii::$app->controller->action->id;
        ?>
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light px-4 px-lg-5 py-3 py-lg-0 fixed-top border-bottom border-primary border-1">
                <a href="<?= Url::toRoute(['site/index']) ?>" class="navbar-brand p-0">
                    <h1 class="text-primary"><i class="fas fa-graduation-cap me-3"></i>LearnQuiz</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="navbar-nav ms-auto py-0">
                    <a href="<?= Url::toRoute(['site/index']) ?>"
                       class="nav-item nav-link text-secondary <?= Yii::$app->controller->id === 'site' && $currentAction === 'index' ? 'active' : '' ?>">
                        <i class="fa fa-home text-primary me-2"></i>Home
                    </a>
                    <a href="<?= Url::toRoute(['site/about']) ?>"
                       class="nav-item nav-link text-secondary <?= Yii::$app->controller->id === 'site' && $currentAction === 'about' ? 'active' : '' ?>">
                        <i class="fa fa-info text-primary me-2"></i>Sobre nós
                    </a>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <a href="<?= Url::to(['jogo/index']) ?>"
                           class="nav-item nav-link text-secondary <?= Yii::$app->controller->id === 'jogo' && $currentAction === 'index' ? 'active' : '' ?>">
                            <i class="fa fa-puzzle-piece text-primary me-2"></i>Os Meus Jogos
                        </a>
                        <a href="<?= Url::toRoute(['jogo/favoritos']) ?>"
                           class="nav-item nav-link text-secondary <?= Yii::$app->controller->id === 'jogo' && $currentAction === 'favoritos' ? 'active' : '' ?>">
                            <i class="fa fa-star text-primary me-2"></i> Favoritos
                        </a>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= Url::toRoute(['site/login']) ?>"
                           class="nav-item nav-link text-secondary <?= $currentAction == 'login' ? 'active' : '' ?>">
                            <i class="fa fa-sign-in-alt text-primary me-2"></i>Login
                        </a>
                        <a href="<?= Url::toRoute(['site/signup']) ?>"
                           class="nav-item nav-link text-secondary <?= $currentAction == 'signup' ? 'active' : '' ?>">
                            <i class="fa fa-user text-primary me-2"></i>Registo
                        </a>
                    <?php else: ?>
                        <span class="text-white nav-item nav-link disabled fw-bold d-inline-flex align-items-center">
                                <i class="fa fa-user text-primary me-2"></i>
                                <span class="text-secondary"><?= Html::encode(Yii::$app->user->identity->username) ?></span>
                            </span>
                        <div class="nav-item">
                            <?= Html::beginForm(['site/logout'], 'post', ['class' => 'd-inline']) ?>
                            <button type="submit"
                                    class="nav-item nav-link btn btn-link text-dark d-inline-flex align-items-center text-secondary">
                                <i class="fa fa-sign-out-alt text-primary me-2"></i>Logout
                            </button>
                            <?= Html::endForm() ?>
                        </div>
                    <?php endif; ?>
                </div>
        </div>
        </nav>
        </div>
    </header>
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <br>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();

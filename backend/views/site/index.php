<?php

/** @var yii\web\View $this */

$this->title = 'Painel de Controlo - LearnQuiz';
$this->registerCssFile('@web/css/site.css');
$this->registerCssFile('@web/css/bootstrap.min.css');
?>

<div class="site-index bg-dark">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div id="flash-error-alert" class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Aviso
                    </h5>
                    <p class="mb-0">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('flash-error-alert');
                if (alert) {
                    alert.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.remove();
                        }
                    }, 6000);
                }
            });
        </script>
    <?php endif; ?>

    <div class="jumbotron text-center bg-light">
        <h1 class="display-4 text-primary">Bem-vindo ao Painel de Controlo!</h1>
        <h4 class="text-secondary">Sistema de gestão de quizzes educacionais</h4>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-primary""></i>
                        <h5 class="text-primary">Utilizadores</h5>
                        <p class="text-primary">Gerir utilizadores e permissões</p>
                        <a href="<?= \yii\helpers\Url::to(['/user/index']) ?>" class="btn btn-primary text-white">Gerir utilizadores</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-question-circle fa-3x mb-3 text-primary"></i>
                        <h5 class="text-primary">Quizzes</h5>
                        <p class="text-primary">Gerir e criar quizzes</p>
                        <a href="#" class="btn btn-primary text-white">Gerir quizzes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

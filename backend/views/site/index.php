<?php

/** @var yii\web\View $this */
/** @var bool $accessDenied */
/** @var string $accessDeniedMessage */

$this->title = 'Painel Admin - LearnQuiz';
?>
<div class="site-index">
    <!-- Alerta JavaScript para acesso negado -->
    <?php if (isset($accessDenied) && $accessDenied): ?>
        <div id="access-denied-alert" class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Acesso Restrito
                    </h5>
                    <p class="mb-0">
                        <?= $accessDeniedMessage ?>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Focar no alerta e fazer scroll suave
                const alert = document.getElementById('access-denied-alert');
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

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4" style="color: #00bfff;">Bem-vindo ao LearnQuiz!</h1>
        <p class="lead" style="color: #ccc;">Sistema de gestão de quizzes educacionais</p>

        <div class="row mt-5">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3" style="color: #00bfff;"></i>
                        <h5 style="color: #f5f5f5;">Utilizadores</h5>
                        <p style="color: #ccc;">Gerir contas e permissões</p>
                        <a href="<?= \yii\helpers\Url::to(['/user/index']) ?>" class="btn btn-primary">Gerir Utilizadores</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-question-circle fa-3x mb-3" style="color: #28a745;"></i>
                        <h5 style="color: #f5f5f5;">Quizzes</h5>
                        <p style="color: #ccc;">Criar e gerir questionários</p>
                        <a href="#" class="btn btn-success">Gerir Quizzes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-3x mb-3" style="color: #ffc107;"></i>
                        <h5 style="color: #f5f5f5;">Estatísticas</h5>
                        <p style="color: #ccc;">Ver relatórios e métricas</p>
                        <a href="#" class="btn btn-warning">Ver Estatísticas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
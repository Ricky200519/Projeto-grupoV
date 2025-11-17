<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Utilizadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile("../../web/css/user-view.css");
$this->registerCssFile("../../web/css/bootstrap.min.css");

$isCurrentUser = $model->id == Yii::$app->user->id;
?>
<div class="user-view">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-sm btn-default text-secondary']) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Informações</h3>
                        </div>
                        <div class="card-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => [
                                    'class' => 'table table-bordered'
                                ],
                                'attributes' => [
                                    'id',
                                    'username',
                                    'email:email',
                                    [
                                        'attribute' => 'status',
                                        'value' => function($model) {
                                            return $model->status == 10 ?
                                                '<span class="badge badge-success">Ativo</span>' :
                                                '<span class="badge badge-danger">Inativo</span>';
                                        },
                                        'format' => 'raw'
                                    ],
                                    [
                                        'label' => 'Role Atual',
                                        'value' => function($model) {
                                            $auth = Yii::$app->authManager;
                                            $roles = $auth->getRolesByUser($model->id);
                                            $roleNames = array_keys($roles);

                                            $badges = [
                                                'admin' => '<span class="badge badge-danger">Admin</span>',
                                                'moderador' => '<span class="badge badge-warning">Moderador</span>',
                                                'participante' => '<span class="badge badge-info">Participante</span>'
                                            ];

                                            $result = [];
                                            foreach ($roleNames as $roleName) {
                                                $result[] = $badges[$roleName] ?? $roleName;
                                            }

                                            return !empty($result) ? implode(' ', $result) : '<span class="badge badge-secondary">Nenhuma</span>';
                                        },
                                        'format' => 'raw'
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'datetime'
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'format' => 'datetime'
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-tag"></i> Alterar Role</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($isCurrentUser): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Não é possível alterar a tua própria role.</strong>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Seleciona a nova role para este utilizador:</p>

                                <div class="d-grid gap-2 ">
                                    <?= Html::a('<i class="fas fa-user-shield"></i> Tornar Moderador', ['view', 'id' => $model->id], [
                                        'class' => 'btn btn-warning btn-lg',
                                        'data' => [
                                            'method' => 'post',
                                            'params' => ['role' => 'moderador'],
                                            'confirm' => 'Tornar este utilizador Moderador? Terá acesso ao backend mas não poderá gerir utilizadores.'
                                        ]
                                    ]) ?>

                                    <?= Html::a('<i class="fas fa-user"></i> Tornar Participante', ['view', 'id' => $model->id], [
                                        'class' => 'btn btn-info btn-lg',
                                        'data' => [
                                            'method' => 'post',
                                            'params' => ['role' => 'participante'],
                                            'confirm' => 'Tornar este utilizador Participante? Perderá acesso ao backend.'
                                        ]
                                    ]) ?>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Nota:</strong> O sistema suporta apenas um administrador.
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
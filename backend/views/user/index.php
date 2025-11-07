<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Utilizadores';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="user-index">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                <div class="card-tools">
                    <?= Html::a('<i class="fas fa-plus"></i> Criar Utilizador', ['create'], ['class' => 'btn btn-sm btn-success mr-2']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt"></i> Recarregar', ['index'], ['class' => 'btn btn-sm btn-default']) ?>
                </div>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => '{items} {pager}',
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => '#',
                        ],
                        'id',
                        'username',
                        'email:email',
                        [
                            'attribute' => 'role',
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
                            'format' => 'raw',
                            'filter' => [
                                'admin' => 'Admin',
                                'moderador' => 'Moderador',
                                'participante' => 'Participante'
                            ]
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => function($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            },
                            'filter' => false
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
                            'header' => 'Ações',
                            'buttons' => [
                                'view' => function($url, $model) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'class' => 'btn btn-sm btn-primary',
                                        'title' => 'Ver detalhes',
                                        'data-toggle' => 'tooltip'
                                    ]);
                                },
                                'delete' => function($url, $model) {
                                    $isCurrentUser = $model->id == Yii::$app->user->id;

                                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                                        'class' => 'btn btn-sm btn-danger' . ($isCurrentUser ? ' disabled' : ''),
                                        'title' => $isCurrentUser ? 'Não podes eliminar a tua própria conta' : 'Eliminar utilizador',
                                        'data' => [
                                            'confirm' => $isCurrentUser ? null : 'Tens a certeza que queres eliminar este utilizador? Esta ação não pode ser revertida.',
                                            'method' => 'post',
                                        ],
                                        'onclick' => $isCurrentUser ? 'return false;' : '',
                                        'data-toggle' => 'tooltip'
                                    ]);
                                }
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

<?php
// Ativar tooltips
$this->registerJs("
    $(function () {
        $('[data-toggle=\"tooltip\"]').tooltip();
    });
");
?>
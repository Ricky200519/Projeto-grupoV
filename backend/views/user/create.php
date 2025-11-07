<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Criar Novo Utilizador';
$this->params['breadcrumbs'][] = ['label' => 'Utilizadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="user-create">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-sm btn-default']) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <?php $form = ActiveForm::begin([
                        'id' => 'create-user-form',
                        'enableClientValidation' => true,
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username', [
                                'template' => '
                                    {label}
                                    {input}
                                    {error}
                                    <div class="help-block">Nome de utilizador único para login</div>
                                '
                            ])->textInput([
                                'maxlength' => true,
                                'placeholder' => 'ex: joao.silva',
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'email', [
                                'template' => '
                                    {label}
                                    {input}
                                    {error}
                                    <div class="help-block">Email válido para notificações</div>
                                '
                            ])->textInput([
                                'maxlength' => true,
                                'type' => 'email',
                                'placeholder' => 'ex: joao@empresa.pt',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'password', [
                                'template' => '
                                    {label}
                                    {input}
                                    {error}
                                    <div class="help-block">Mínimo 6 caracteres</div>
                                '
                            ])->passwordInput([
                                'maxlength' => true,
                                'placeholder' => '••••••',
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Tipo de Utilizador</label>
                                <div class="form-control" style="border: none; background: none; padding-left: 0;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_moderador" value="moderador" checked>
                                        <label class="form-check-label" for="role_moderador">
                                            <span class="badge badge-warning">Moderador</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_participante" value="participante">
                                        <label class="form-check-label" for="role_participante">
                                            <span class="badge badge-info">Participante</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="help-block">
                                    <small class="text-muted">
                                        <strong>Moderador:</strong> Acesso ao backend + criar quizzes<br>
                                        <strong>Participante:</strong> Apenas criar e jogar quizzes
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Criar Utilizador', [
                            'class' => 'btn btn-success btn-lg',
                            'name' => 'create-button'
                        ]) ?>

                        <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'], [
                            'class' => 'btn btn-default btn-lg ml-2'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Informação
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Permissões por Role:</strong></p>

                            <div class="mb-3">
                                <span class="badge badge-warning">Moderador</span>
                                <ul class="mt-2 small">
                                    <li>Acesso ao backend</li>
                                    <li>Ver todos os quizzes</li>
                                    <li>Gerir todos os quizzes</li>
                                    <li>Criar e jogar quizzes</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <span class="badge badge-info">Participante</span>
                                <ul class="mt-2 small">
                                    <li>Criar quizzes</li>
                                    <li>Jogar quizzes</li>
                                    <li>Gerir apenas os seus quizzes</li>
                                </ul>
                            </div>

                            <div class="alert alert-warning small mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Nota:</strong> Apenas pode existir um administrador no sistema.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><?php

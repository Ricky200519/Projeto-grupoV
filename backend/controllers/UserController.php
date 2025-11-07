<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;
use backend\models\UserSearch;

class UserController extends Controller
{
    public function beforeAction($action)
    {
        // 🔒 Só admin pode aceder a gestão de utilizadores
        if (!Yii::$app->user->can('admin') && in_array($action->id, ['index', 'view', 'delete', 'create'])) {
            Yii::$app->session->set('accessDeniedMessage', 'Não tem permissões para gerir utilizadores. Apenas administradores podem aceder a esta funcionalidade.');
            return $this->redirect(['/site/index']);
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            // Definir password e auth key
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->generateEmailVerificationToken();
            $model->status = User::STATUS_ACTIVE;

            if ($model->save()) {
                // 🔽 ATRIBUIÇÃO DIRETA DA ROLE (como no exemplo)
                $roleName = Yii::$app->request->post('role', 'participante');

                // Garantir que apenas roles permitidas são atribuídas
                if (in_array($roleName, ['moderador', 'participante'])) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($roleName);

                    if ($role) {
                        $auth->assign($role, $model->id);
                    }
                }

                Yii::$app->session->setFlash('success',
                    "Utilizador <strong>{$model->username}</strong> criado com sucesso com role <strong>{$roleName}</strong>!"
                );
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error',
                    "Erro ao criar utilizador: " . implode(', ', $model->getFirstErrors())
                );
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        // 🔒 IMPEDIR que o admin veja/altere a sua própria role
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->set('accessDeniedMessage', 'Não podes ver ou alterar a tua própria role.');
            return $this->redirect(['/site/index']);
        }

        $model = $this->findModel($id);

        // Processar mudança de role se for POST
        if (Yii::$app->request->isPost) {
            $newRole = Yii::$app->request->post('role');

            // 🔒 IMPEDIR criar novos admins
            if ($newRole === 'admin') {
                Yii::$app->session->setFlash('error',
                    'Não é possível criar novos administradores. O sistema suporta apenas um administrador.'
                );
                return $this->redirect(['view', 'id' => $id]);
            }

            if ($newRole && in_array($newRole, ['moderador', 'participante'])) {
                $auth = Yii::$app->authManager;

                // Remover roles atuais
                $auth->revokeAll($id);

                // Atribuir nova role
                $role = $auth->getRole($newRole);
                $auth->assign($role, $id);

                Yii::$app->session->setFlash('success',
                    "Role do utilizador <strong>{$model->username}</strong> alterada para <strong>{$newRole}</strong>!"
                );
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        // 🔒 IMPEDIR que users se apaguem a si mesmos
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->set('accessDeniedMessage', 'Não podes eliminar a tua própria conta.');
            return $this->redirect(['/site/index']);
        }

        $model = $this->findModel($id);
        $username = $model->username;

        try {
            // Remover roles primeiro
            $auth = Yii::$app->authManager;
            $auth->revokeAll($id);

            // Eliminar o utilizador
            if ($model->delete()) {
                Yii::$app->session->setFlash('success',
                    "Utilizador <strong>{$username}</strong> eliminado com sucesso!"
                );
            } else {
                Yii::$app->session->setFlash('error',
                    "Erro ao eliminar o utilizador <strong>{$username}</strong>."
                );
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error',
                "Erro ao eliminar o utilizador: " . $e->getMessage()
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Utilizador não encontrado.');
    }
}
<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        echo "A criar permissões...\n";

        $quizCreate = $auth->createPermission('quizCreate');
        $quizCreate->description = 'Criar quizzes';
        $auth->add($quizCreate);

        $quizUpdate = $auth->createPermission('quizUpdate');
        $quizUpdate->description = 'Editar quizzes';
        $auth->add($quizUpdate);

        $quizDelete = $auth->createPermission('quizDelete');
        $quizDelete->description = 'Eliminar quizzes';
        $auth->add($quizDelete);

        $quizPlay = $auth->createPermission('quizPlay');
        $quizPlay->description = 'Jogar quizzes';
        $auth->add($quizPlay);

        $viewQuiz = $auth->createPermission('quizView');
        $viewQuiz->description = 'Visualizar quizzes';
        $auth->add($viewQuiz);

        $quizViewAll = $auth->createPermission('quizViewAll');
        $quizViewAll->description = 'Ver todos os quizzes';
        $auth->add($quizViewAll);

        $quizManageAll = $auth->createPermission('quizManageAll');
        $quizManageAll->description = 'Gerir todos os quizzes';
        $auth->add($quizManageAll);

        $userPromote = $auth->createPermission('userPromote');
        $userPromote->description = 'Promover/demorover utilizadores';
        $auth->add($userPromote);

        $userDelete = $auth->createPermission('userDelete');
        $userDelete->description = 'Eliminar utilizadores';
        $auth->add($userDelete);

        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Aceder ao backend';
        $auth->add($accessBackend);

        echo "A criar papéis...\n";

        $participante = $auth->createRole('participante');
        $auth->add($participante);
        $auth->addChild($participante, $quizCreate);
        $auth->addChild($participante, $quizUpdate);
        $auth->addChild($participante, $quizDelete);
        $auth->addChild($participante, $quizPlay);
        $auth->addChild($participante, $viewQuiz);

        $moderador = $auth->createRole('moderador');
        $auth->add($moderador);
        $auth->addChild($moderador, $participante);
        $auth->addChild($moderador, $quizViewAll);
        $auth->addChild($moderador, $quizManageAll);
        $auth->addChild($moderador, $accessBackend);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $moderador);
        $auth->addChild($admin, $userPromote);
        $auth->addChild($admin, $userDelete);
        $auth->removeChild($admin, $quizPlay);

        echo "Papéis criados com sucesso!\n";

        $this->assignRoleByEmail('admin@quiz.pt', 'admin');
        $this->assignRoleByEmail('moderador@quiz.pt', 'moderador');
        $this->assignRoleByEmail('participante@quiz.pt', 'participante');

        echo "RBAC inicializado com sucesso!\n";
    }

    public function actionAssignRoleToNewUser($userId, $roleName = 'participante')
    {
        $auth = Yii::$app->authManager;
        $user = User::findOne($userId);

        if (!$user) {
            echo "ERRO: Utilizador com ID $userId não encontrado.\n";
            return false;
        }

        $role = $auth->getRole($roleName);
        if (!$role) {
            echo "ERRO: Role '$roleName' não encontrada.\n";
            return false;
        }

        $auth->revokeAll($userId);

        $auth->assign($role, $userId);

        echo "Role '$roleName' atribuída com sucesso ao utilizador {$user->username} (ID: $userId)\n";
        return true;
    }

    public function actionAssignRoleByEmail($email, $roleName)
    {
        $user = User::findOne(['email' => $email]);

        if (!$user) {
            echo "ERRO: Utilizador com email $email não encontrado.\n";
            return false;
        }

        return $this->actionAssignRoleToNewUser($user->id, $roleName);
    }

    protected function assignRoleByEmail($email, $roleName)
    {
        $auth = Yii::$app->authManager;
        $user = User::findOne(['email' => $email]);

        if ($user) {
            $role = $auth->getRole($roleName);
            if ($role) {
                $auth->revokeAll($user->id);
                $auth->assign($role, $user->id);
                echo "Role '$roleName' atribuída a $email\n";
            }
        } else {
            echo "AVISO: Utilizador $email não encontrado\n";
        }
    }

    public function actionAssign($email, $role)
    {
        $this->assignRoleByEmail($email, $role);
    }

    public function actionCheckRoles()
    {
        $auth = Yii::$app->authManager;

        $users = User::find()->all();

        foreach ($users as $user) {
            $roles = $auth->getRolesByUser($user->id);
            $roleNames = [];

            foreach ($roles as $role) {
                $roleNames[] = $role->name;
            }

            echo "{$user->username} (ID: {$user->id}, Email: {$user->email}): " .
                (empty($roleNames) ? 'Nenhuma role' : implode(', ', $roleNames)) . "\n";
        }
    }
}
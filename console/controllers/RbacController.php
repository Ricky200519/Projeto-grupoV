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

        // Permissões para Quizzes
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

        $quizViewAll = $auth->createPermission('quizViewAll');
        $quizViewAll->description = 'Ver todos os quizzes';
        $auth->add($quizViewAll);

        $quizManageAll = $auth->createPermission('quizManageAll');
        $quizManageAll->description = 'Gerir todos os quizzes';
        $auth->add($quizManageAll);

        // Permissões para Users
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

        // Participante
        $participante = $auth->createRole('participante');
        $auth->add($participante);
        $auth->addChild($participante, $quizCreate);
        $auth->addChild($participante, $quizUpdate); // pode editar os seus
        $auth->addChild($participante, $quizDelete); // pode eliminar os seus
        $auth->addChild($participante, $quizPlay);

        // Moderador
        $moderador = $auth->createRole('moderador');
        $auth->add($moderador);
        $auth->addChild($moderador, $participante); // herda tudo do participante
        $auth->addChild($moderador, $quizViewAll);
        $auth->addChild($moderador, $quizManageAll);
        $auth->addChild($moderador, $accessBackend);

        // Admin
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $moderador); // herda tudo do moderador
        $auth->addChild($admin, $userPromote);
        $auth->addChild($admin, $userDelete);

        echo "Papéis criados com sucesso!\n";

        // Atribuir roles pelos emails corretos
        $this->assignRoleByEmail('admin@quiz.pt', 'admin');
        $this->assignRoleByEmail('moderador@quiz.pt', 'moderador');
        $this->assignRoleByEmail('participante@quiz.pt', 'participante');

        echo "RBAC inicializado com sucesso!\n";
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

    // Comando para atribuir role a um utilizador específico
    public function actionAssign($email, $role)
    {
        $this->assignRoleByEmail($email, $role);
    }

    // Comando para verificar as roles dos utilizadores
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
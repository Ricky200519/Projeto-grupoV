<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class TestController extends Controller
{
    public function actionCreateUsers()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@quiz.pt',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'username' => 'moderador',
                'email' => 'moderador@quiz.pt',
                'password' => 'moderador123',
                'role' => 'moderador'
            ],
            [
                'username' => 'participante',
                'email' => 'participante@quiz.pt',
                'password' => 'participante123',
                'role' => 'participante'
            ]
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->setPassword($userData['password']);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->status = User::STATUS_ACTIVE;

            if ($user->save()) {
                echo "Utilizador {$userData['username']} criado com sucesso!\n";

                // Atribuir role
                $this->assignRole($user->id, $userData['role']);
            } else {
                echo "Erro ao criar utilizador {$userData['username']}: " . implode(', ', $user->getFirstErrors()) . "\n";
            }
        }

        return ExitCode::OK;
    }

    protected function assignRole($userId, $roleName)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);

        if ($role) {
            $auth->revokeAll($userId);
            $auth->assign($role, $userId);
            echo "   Role '$roleName' atribuída\n";
        }
    }
}
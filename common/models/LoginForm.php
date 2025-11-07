<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            // Verificar se é tentativa de login no backend
            if (Yii::$app->id === 'app-backend') {
                $user = $this->getUser();

                // Verificar se o utilizador tem permissão para aceder ao backend
                if (!$this->hasBackendAccess($user)) {
                    $this->addError('password', 'Não tem permissões para aceder ao backend. Apenas administradores e moderadores podem aceder.');
                    return false;
                }
            }

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Verifica se o utilizador tem permissão para aceder ao backend
     *
     * @param User|null $user
     * @return bool
     */
    protected function hasBackendAccess($user)
    {
        if (!$user) {
            return false;
        }

        // Verificar roles do utilizador
        $auth = Yii::$app->authManager;
        $userRoles = $auth->getRolesByUser($user->id);

        // Lista de roles permitidas no backend
        $allowedRoles = ['admin', 'moderador'];

        foreach ($userRoles as $role) {
            if (in_array($role->name, $allowedRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}

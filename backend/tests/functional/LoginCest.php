<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
    }

    public function loginvalido(FunctionalTester $I)
    {
        $I->see('Login', 'h1');

        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin123');

        $I->click('login-button');

        $I->see('Bem-vindo ao Painel de Controlo!');
    }

    public function loginInvalido(FunctionalTester $I)
    {
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'errado');

        $I->click('login-button');

        $I->see('Incorrect username or password.');
    }

    public function loginSemAcesso(FunctionalTester $I)
    {
        $I->fillField('LoginForm[username]', 'participante');
        $I->fillField('LoginForm[password]', 'participante123');

        $I->click('login-button');

        $I->see('Não tem permissões para aceder ao backend. Apenas administradores e moderadores podem aceder.');
    }
}
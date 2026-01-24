<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\models\Jogo;

class JogoCest
{
    public function criarJogo(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'moderador');
        $I->fillField('LoginForm[password]', 'moderador123');
        $I->click(['name' => 'login-button']);
        $I->click('Login');
        $I->see('Logout');
        $I->see('Bem-vindo ao LearnQuiz');
        $I->click(['css' => 'a[href$="/jogo/index"]']);
        $I->see('Os meus Jogos', 'h2');
        $I->click('+ Criar Novo Jogo');
        $I->see('Criar Novo Jogo', 'h1');
        $I->fillField('Jogo[titulo]', 'Jogo Teste');
        $I->fillField('Jogo[descricao]', 'Descrição de teste para jogo público');
        $I->click('Criar Jogo');
        $I->seeInCurrentUrl('/jogo/index');
        $I->see('Jogo Teste');
        $I->seeRecord(Jogo::class, [
            'titulo' => 'Jogo Teste',
            'IsPublic' => 1,
        ]);
    }
}
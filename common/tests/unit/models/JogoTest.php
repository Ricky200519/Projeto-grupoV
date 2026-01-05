<?php

namespace common\tests\unit\models;

use common\models\Jogo;

class JogoTest extends \Codeception\Test\Unit
{
    public function testTituloObrigatorio()
    {
        $jogo = new Jogo();
        $jogo->descricao = "Descrição teste";

        $this->assertFalse($jogo->validate());
        $this->assertArrayHasKey('titulo', $jogo->errors);
    }

    public function testTituloMaximo100Caracteres()
    {
        $jogo = new Jogo();
        $jogo->titulo = str_repeat('A', 101);

        $this->assertFalse($jogo->validate());
        $this->assertArrayHasKey('titulo', $jogo->errors);
    }

    public function testDescricaoPodeSerNula()
    {
        $jogo = new Jogo();
        $jogo->titulo = "Jogo Teste";
        $jogo->descricao = null;

        $this->assertTrue($jogo->validate(['descricao']));
    }

    public function testIsPublicDefaultValue()
    {
        $jogo = new Jogo();
        $jogo->titulo = "Jogo Teste";

        $jogo->validate();

        $this->assertEquals(1, $jogo->IsPublic);
    }

    public function testCamposInteiros()
    {
        $jogo = new Jogo();
        $jogo->titulo = "Teste";
        $jogo->autor_id = "abc";

        $this->assertFalse($jogo->validate());
        $this->assertArrayHasKey('autor_id', $jogo->errors);
    }

    public function testRelacoesExistem()
    {
        $jogo = new Jogo();

        $this->assertNotNull($jogo->getPerguntas());
        $this->assertNotNull($jogo->getAutor());
        $this->assertNotNull($jogo->getRatings());
    }

    public function testCorMedalha()
    {
        $jogo = new Jogo();

        $this->assertEquals('gold', $jogo->getCorMedalha(1));
        $this->assertEquals('silver', $jogo->getCorMedalha(2));
        $this->assertEquals('#cd7f32', $jogo->getCorMedalha(3));
        $this->assertEquals('black', $jogo->getCorMedalha(10));
        $this->assertEquals('black', $jogo->getCorMedalha(null));
    }
}
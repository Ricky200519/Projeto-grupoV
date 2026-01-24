<?php

namespace common\tests\unit\models;

use common\models\Pergunta;

class PerguntaTest extends \Codeception\Test\Unit
{
    public function testTextoObrigatorio()
    {
        $p = new Pergunta();
        $p->tempolimite = 30;
        $p->pontosvalor = 10;

        $this->assertFalse($p->validate());
        $this->assertArrayHasKey('texto', $p->errors);
    }

    public function testTextoMaximo500Caracteres()
    {
        $p = new Pergunta();
        $p->texto = str_repeat('A', 501);

        $this->assertFalse($p->validate());
        $this->assertArrayHasKey('texto', $p->errors);
    }

    public function testCamposInteiros()
    {
        $p = new Pergunta();
        $p->texto = "Pergunta válida";
        $p->tempolimite = "abc";
        $p->pontosvalor = 10;

        $this->assertFalse($p->validate());
        $this->assertArrayHasKey('tempolimite', $p->errors);
    }

    public function testModeloValido()
    {
        $p = new Pergunta();
        $p->texto = "Qual é a capital de Portugal?";
        $p->tempolimite = 30;
        $p->pontosvalor = 10;
        $p->temporesposta = 5;
        $p->jogo_id = 1;

        $this->assertTrue($p->validate());
    }

    public function testRelacoesExistem()
    {
        $p = new Pergunta();
        $this->assertNotNull($p->getRespostas());
        $this->assertNotNull($p->getOpcaoescolhidas());
        $this->assertNotNull($p->getJogo());
        $this->assertNotNull($p->getRespostaCorreta());
    }
}
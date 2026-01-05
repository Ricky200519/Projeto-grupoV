<?php

namespace common\tests\unit\models;

use common\models\Resposta;

class RespostaTest extends \Codeception\Test\Unit
{
    public function testTextoObrigatorio()
    {
        $r = new Resposta();
        $r->pergunta_id = 1;

        $this->assertFalse($r->validate());
        $this->assertArrayHasKey('texto', $r->errors);
    }

    public function testTextoMaximo255Caracteres()
    {
        $r = new Resposta();
        $r->texto = str_repeat('A', 256);
        $r->pergunta_id = 1;

        $this->assertFalse($r->validate());
        $this->assertArrayHasKey('texto', $r->errors);
    }

    public function testCamposInteiros()
    {
        $r = new Resposta();
        $r->texto = "Resposta válida";
        $r->pergunta_id = "abc";

        $this->assertFalse($r->validate());
        $this->assertArrayHasKey('pergunta_id', $r->errors);
    }

    public function testModeloValidoSemPergunta()
    {
        $r = new Resposta();
        $r->texto = "Resposta válida";

        $this->assertTrue($r->validate());
    }

    public function testRelacaoPerguntaExiste()
    {
        $r = new Resposta();
        $this->assertNotNull($r->getPergunta());
    }
}
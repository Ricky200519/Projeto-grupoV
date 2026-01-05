<?php

namespace common\tests\unit\models;

use common\models\Jogador;

class JogadorTest extends \Codeception\Test\Unit
{
    public function testCamposObrigatorios()
    {
        $j = new Jogador();

        $this->assertFalse($j->validate());
        $this->assertArrayHasKey('user_id', $j->errors);
        $this->assertArrayHasKey('jogo_id', $j->errors);
    }

    public function testCamposInteiros()
    {
        $j = new Jogador();
        $j->user_id = "abc";
        $j->jogo_id = 1;
        $j->pontuacao = "dez";

        $this->assertFalse($j->validate());
        $this->assertArrayHasKey('user_id', $j->errors);
        $this->assertArrayHasKey('pontuacao', $j->errors);
    }
    public function testRelacoesExistem()
    {
        $j = new Jogador();

        $this->assertNotNull($j->getUser());
        $this->assertNotNull($j->getJogo());
    }

    public function testObterPosicaoRankingSemBD()
    {
        $pos = Jogador::obterPosicaoRanking(1, 1);

        $this->assertNull($pos);
    }
}
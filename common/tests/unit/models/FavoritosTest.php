<?php

namespace common\tests\unit\models;

use common\models\Favoritos;

class FavoritosTest extends \Codeception\Test\Unit
{
    public function testCamposObrigatorios()
    {
        $fav = new Favoritos();

        $this->assertFalse($fav->validate());
        $this->assertArrayHasKey('user_id', $fav->errors);
        $this->assertArrayHasKey('jogo_id', $fav->errors);
    }

    public function testCamposInteiros()
    {
        $fav = new Favoritos();
        $fav->user_id = "abc";
        $fav->jogo_id = 2;

        $this->assertFalse($fav->validate());
        $this->assertArrayHasKey('user_id', $fav->errors);
    }

    public function testRelacoesExistem()
    {
        $fav = new Favoritos();

        $this->assertNotNull($fav->getUser());
        $this->assertNotNull($fav->getJogo());
    }
}
<?php

namespace common\models;

use common\models\OpcaoEscolhida;

class Pergunta extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'pergunta';
    }

    public function rules()
    {
        return [
            [['texto'], 'required'],
            [['tempolimite', 'pontosvalor', 'temporesposta', 'jogo_id'], 'integer'],
            [['texto'], 'string', 'max' => 500],
        ];
    }

    public function getRespostas()
    {
        return $this->hasMany(Resposta::class, ['pergunta_id' => 'id']);
    }

    public function getOpcaoescolhidas()
    {
        return $this->hasMany(OpcaoEscolhida::class, ['pergunta_id' => 'id']);
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id' => 'jogo_id']);
    }

    public function getRespostaCorreta()
    {
        return $this->hasOne(Resposta::class, ['pergunta_id' => 'id'])
            ->where(['correta' => 1]);
    }

}

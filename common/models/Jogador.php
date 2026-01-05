<?php

namespace common\models;

use Yii;

class Jogador extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'jogador';
    }

    public function rules()
    {
        return [
            [['user_id', 'jogo_id'], 'required'],
            [['user_id', 'jogo_id', 'pontuacao'], 'integer'],
            [['data_ultima_tentativa'], 'safe'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['jogo_id', 'exist', 'targetClass' => Jogo::class, 'targetAttribute' => ['jogo_id' => 'id']],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id' => 'jogo_id']);
    }
    public static function obterPosicaoRanking($jogoId, $userId)
    {
        $ranking = self::find()
            ->where(['jogo_id' => $jogoId])
            ->orderBy(['pontuacao' => SORT_DESC])
            ->all();

        $pos = 1;
        foreach ($ranking as $j) {
            if ($j->user_id == $userId) {
                return $pos;
            }
            $pos++;
        }

        return null;
    }
}

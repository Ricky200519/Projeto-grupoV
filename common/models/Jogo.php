<?php

namespace common\models;

use frontend\models\Sala;
use Yii;

class Jogo extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'jogo';
    }

    public function rules()
    {
        return [
            [['descricao', 'autor_id'], 'default', 'value' => null],
            [['IsPublic'], 'default', 'value' => 1],
            [['titulo'], 'required'],
            [['descricao'], 'string'],
            [['datacriacao'], 'safe'],
            [['autor_id', 'IsPublic'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'datacriacao' => 'Datacriacao',
            'autor_id' => 'Autor ID',
            'IsPublic' => 'Is Public',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->datacriacao = date('Y-m-d H:i:s');
                $this->autor_id = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }

    public function getPerguntas()
    {
        return $this->hasMany(Pergunta::class, ['jogo_id' => 'id']);
    }

    public function getSalas()
    {
        return $this->hasMany(Sala::class, ['jogo_id' => 'id']);
    }

    public function getAutor()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'autor_id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Rating::class, ['jogo_id' => 'id']);
    }

    public function getTentativas($userId)
    {
        return Tentativa::find()
            ->where(['jogo_id' => $this->id, 'jogador_id' => $userId])
            ->count();
    }

    public function getMelhorPontuacao($userId)
    {
        return Jogador::find()
            ->where(['jogo_id' => $this->id, 'user_id' => $userId])
            ->max('pontuacao');
    }

    public function getRanking()
    {
        return Jogador::find()
            ->where(['jogo_id' => $this->id])
            ->orderBy(['pontuacao' => SORT_DESC])
            ->all();
    }

    public function getTop3()
    {
        $ranking = $this->getRanking();
        return array_map(function ($j) {
            return [
                'username' => $j->user->username,
                'pontuacao' => $j->pontuacao,
            ];
        }, array_slice($ranking, 0, 3));
    }

    public function getPosicaoJogador($userId)
    {
        $ranking = $this->getRanking();
        $rank = 1;

        foreach ($ranking as $j) {
            if ($j->user_id == $userId) {
                return $rank;
            }
            $rank++;
        }

        return null;
    }

    public function getMediaRating()
    {
        return $this->getRatings()->average('estrelas');
    }

    public function getTotalRatings()
    {
        return $this->getRatings()->count();
    }

    public function getUserRating($userId)
    {
        return Rating::find()
            ->where(['jogo_id' => $this->id, 'user_id' => $userId])
            ->one();
    }

    public function getCorMedalha($posicao)
    {
        if (!$posicao) {
            return 'black';
        }

        return match ($posicao) {
            1 => 'gold',
            2 => 'silver',
            3 => '#cd7f32',
            default => 'black',
        };
    }

    public function getTopRanking($limit = 10)
    {
        return Jogador::find()
            ->where(['jogo_id' => $this->id])
            ->orderBy(['pontuacao' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}

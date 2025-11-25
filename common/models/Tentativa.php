<?php

namespace common\models;

use Yii;
use common\models\OpcaoEscolhida;

/**
 * This is the model class for table "tentativas".
 *
 * @property int $id
 * @property int|null $jogador_id
 * @property int|null $jogo_id
 * @property string|null $datahora
 *
 * @property User $jogador
 * @property Jogo $jogo
 * @property Opcaoescolhida[] $opcaoescolhidas
 */
class Tentativa extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tentativas';
    }

    public function rules()
    {
        return [
            [['jogador_id', 'jogo_id'], 'default', 'value' => null],
            [['jogador_id', 'jogo_id'], 'integer'],
            [['datahora'], 'safe'],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::class, 'targetAttribute' => ['jogo_id' => 'id']],
            [['jogador_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::class, 'targetAttribute' => ['jogador_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jogador_id' => 'Jogador',
            'jogo_id' => 'Jogo',
            'datahora' => 'Data/Hora',
        ];
    }

    public function isAnswered()
    {
      return OpcaoEscolhida::find()->where(['tentativa_id' => $this->id])->exists();
    }

    public function getJogador()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'jogador_id']);
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id' => 'jogo_id']);
    }

    public function getOpcaoescolhidas()
    {
        return $this->hasMany(OpcaoEscolhida::class, ['tentativa_id' => 'id']);
    }

}


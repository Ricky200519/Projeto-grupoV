<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "opcaoescolhida".
 *
 * @property int $id
 * @property int|null $resposta_id
 * @property int|null $jogador_id
 * @property int|null $tentativa_id
 * @property string|null $datahora
 * @property int|null $pergunta_id
 *
 * @property User $jogador
 * @property Resposta $resposta
 * @property Tentativa $tentativa
 * @property Pergunta $pergunta
 */
class OpcaoEscolhida extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'opcaoescolhida';
    }

    public function rules()
    {
        return [
            [['resposta_id', 'jogador_id', 'tentativa_id', 'pergunta_id'], 'default', 'value' => null],
            [['resposta_id', 'jogador_id', 'tentativa_id', 'pergunta_id'], 'integer'],
            [['datahora'], 'safe'],

            [['jogador_id'], 'exist', 'skipOnError' => true,
                'targetClass' => \common\models\User::class,
                'targetAttribute' => ['jogador_id' => 'id']
            ],

            [['tentativa_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Tentativa::class,
                'targetAttribute' => ['tentativa_id' => 'id']
            ],

            [['resposta_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Resposta::class,
                'targetAttribute' => ['resposta_id' => 'id']
            ],

            [['pergunta_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Pergunta::class,
                'targetAttribute' => ['pergunta_id' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resposta_id' => 'Resposta',
            'jogador_id' => 'Jogador',
            'tentativa_id' => 'Tentativa',
            'datahora' => 'Data/Hora',
            'pergunta_id' => 'Pergunta',
        ];
    }

    public function getJogador()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'jogador_id']);
    }

    public function getResposta()
    {
        return $this->hasOne(Resposta::class, ['id' => 'resposta_id']);
    }

    public function getTentativa()
    {
        return $this->hasOne(Tentativa::class, ['id' => 'tentativa_id']);
    }

    public function getPergunta()
    {
        return $this->hasOne(Pergunta::class, ['id' => 'pergunta_id']);
    }
}

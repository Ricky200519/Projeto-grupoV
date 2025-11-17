<?php

namespace common\models;

use frontend\models\Opcaoescolhida;

/**
 * This is the model class for table "pergunta".
 *
 * @property int $id
 * @property string $texto
 * @property int|null $tempolimite
 * @property int|null $pontosvalor
 * @property int|null $temporesposta
 * @property int|null $jogo_id
 *
 * @property Jogo $jogo
 * @property Opcaoescolhida[] $opcaoescolhidas
 */
class Pergunta extends \yii\db\ActiveRecord
{
    /**
     * @var mixed|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pergunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['temporesposta', 'jogo_id'], 'default', 'value' => null],
            [['tempolimite'], 'default', 'value' => 20],
            [['pontosvalor'], 'default', 'value' => 10],
            [['texto'], 'required'],
            [['tempolimite', 'pontosvalor', 'temporesposta', 'jogo_id'], 'integer'],
            [['texto'], 'string', 'max' => 500],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::class, 'targetAttribute' => ['jogo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'texto' => 'Texto',
            'tempolimite' => 'Tempolimite',
            'pontosvalor' => 'Pontosvalor',
            'temporesposta' => 'Temporesposta',
            'jogo_id' => 'Jogo ID',
        ];
    }

    /**
     * Gets query for [[Jogo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespostas()
    {
        return $this->hasMany(Resposta::class, ['pergunta_id' => 'id']);
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id' => 'jogo_id']);
    }


    /**
     * Gets query for [[Opcaoescolhidas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpcaoescolhidas()
    {
        return $this->hasMany(Opcaoescolhida::class, ['pergunta_id' => 'id']);
    }

}

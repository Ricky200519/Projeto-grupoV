<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $estrelas
 * @property string|null $dataavaliacao
 * @property int|null $jogo_id
 *
 * @property User $user
 * @property Jogo $jogo
 */
class Rating extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'rating';
    }

    public function rules()
    {
        return [
            [['user_id', 'jogo_id'], 'required'],
            [['user_id', 'jogo_id', 'estrelas'], 'integer'],
            [['estrelas'], 'integer', 'min' => 1, 'max' => 5],
            [['dataavaliacao'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::class, 'targetAttribute' => ['jogo_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Utilizador',
            'estrelas' => 'Estrelas',
            'dataavaliacao' => 'Data de Avaliação',
            'jogo_id' => 'Jogo',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id' => 'jogo_id']);
    }
}

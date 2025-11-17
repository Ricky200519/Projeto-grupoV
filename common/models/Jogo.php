<?php

namespace common\models;

use frontend\models\Sala;
use Yii;

/**
 * This is the model class for table "jogo".
 *
 * @property int $id
 * @property string $titulo
 * @property string|null $descricao
 * @property string|null $datacriacao
 * @property int|null $autor_id
 * @property int $IsPublic
 *
 * @property Pergunta[] $perguntas
 * @property Sala[] $salas
 */
class Jogo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jogo';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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


    /**
     * Gets query for [[Perguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerguntas()
    {
        return $this->hasMany(Pergunta::class, ['jogo_id' => 'id']);
    }

    /**
     * Gets query for [[Salas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalas()
    {
        return $this->hasMany(Sala::class, ['jogo_id' => 'id']);
    }

    public function getAutor()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'autor_id']);
    }



}

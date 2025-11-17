<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resposta".
 *
 * @property int $id
 * @property int $pergunta_id
 * @property string $texto
 * @property int $correta
 *
 * @property Pergunta $pergunta
 */
class Resposta extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'resposta';
    }

    public function rules()
    {
        return [
            [['texto'], 'required'],
            [['pergunta_id', 'correta'], 'integer'],
            [['texto'], 'string', 'max' => 255],

            [
                ['pergunta_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Pergunta::class,
                'targetAttribute' => ['pergunta_id' => 'id']
            ],

            ['texto', 'validateTotalRespostas'],

            ['correta', 'validateCorreta'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'texto' => 'Texto da Resposta',
            'correta' => 'É a resposta certa?',
        ];
    }

    public function getPergunta()
    {
        return $this->hasOne(Pergunta::class, ['id' => 'pergunta_id']);
    }

    public function validateCorreta($attribute, $params)
    {
        if ($this->correta == 1) {
            $existe = Resposta::find()
                ->where(['pergunta_id' => $this->pergunta_id, 'correta' => 1])
                ->andWhere(['<>', 'id', $this->id])
                ->exists();
            if ($existe) {
                $this->addError($attribute, 'Já existe uma resposta correta nesta pergunta.');
            }
        }
    }



    public function validateTotalRespostas($attribute, $params)
    {
        if (!$this->pergunta_id) return;

        $total = Resposta::find()->where(['pergunta_id' => $this->pergunta_id])->count();
        $novoTotal = $this->isNewRecord ? $total + 1 : $total;

        if ($novoTotal > 4) {
            $this->addError($attribute, 'Uma pergunta pode ter no máximo 4 respostas.');
        }
    }





}

<?php
namespace common\models;

use Yii;

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

            [['pergunta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pergunta::class, 'targetAttribute' => ['pergunta_id' => 'id']],

            ['correta', 'validateCorreta'],
            ['texto', 'validateTotalRespostas'],
        ];
    }

    public function validateCorreta($attribute, $params)
    {
        if ($this->$attribute == 1) {
            $existe = self::find()
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

        $total = self::find()->where(['pergunta_id' => $this->pergunta_id])->count();
        $novoTotal = $this->isNewRecord ? $total + 1 : $total;

        if ($novoTotal > 4) {
            $this->addError($attribute, 'Uma pergunta pode ter no máximo 4 respostas.');
        }
    }

    public function getPergunta()
    {
        return $this->hasOne(Pergunta::class, ['id' => 'pergunta_id']);
    }
}

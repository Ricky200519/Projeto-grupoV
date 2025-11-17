<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */
/** @var common\models\Pergunta $pergunta */

$this->title = 'Adicionar resposta à pergunta: ' . $pergunta->texto;

?>
<div class="resposta-create">

    <?= $this->render('_form', [
            'model' => $model,
            'pergunta' => $pergunta,
            'jogo_id' => $jogo_id,
            'total' => $total ?? null,
            'isUpdate' => $isUpdate,
    ]) ?>

</div>

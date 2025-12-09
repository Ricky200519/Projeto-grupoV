<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Resposta $model */
/** @var common\models\Pergunta $pergunta */
/** @var int $jogo_id */
/** @var int|null $total */
/** @var bool $isUpdate */

$this->title = 'Adicionar resposta à pergunta: ' . $pergunta->texto;

?>
<div class="card bg-white mt-4 p-5 mx-auto w-100 border border-primary border-2">
    <?= $this->render('_form', [
            'model' => $model,
            'pergunta' => $pergunta,
            'jogo_id' => $jogo_id,
            'total' => $total ?? null,
            'isUpdate' => $isUpdate,
    ]) ?>
</div>

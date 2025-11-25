<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var common\models\Tentativa $tentativa */
/** @var common\models\Pergunta $pergunta */
/** @var common\models\Resposta[] $respostas */
/** @var bool $isUltimaPergunta */
/** @var int|null $proximaPerguntaId */

$this->title = 'Pergunta do Quiz';

$correta = $pergunta->respostaCorreta;
$corretaId = $correta ? $correta->id : 'null';
?>

<div class="card bg-body p-4 mt-4" style=" margin: 0 auto;">
    <h3 class="text-primary mb-3 text-center">
        <?= Html::encode($pergunta->texto) ?>
    </h3>

    <?php $form = ActiveForm::begin([
            'id' => 'respostaForm',
            'method' => 'post',
            'action' => Url::to(['jogo/pergunta', 'tentativa_id' => $tentativa->id, 'pergunta_id' => $pergunta->id])
    ]); ?>

    <?= Html::hiddenInput('resposta_id', '', [
            'id' => 'resposta_id_input'
    ]); ?>

    <div class="row g-3">
        <?php foreach ($respostas as $resposta): ?>
            <div class="col-md-12">
                <div class="card resposta-card p-3 mb-2"
                     id="card-resposta-<?= $resposta->id ?>"
                     data-resposta-id="<?= $resposta->id ?>"
                     style="cursor: pointer;"
                     onclick="selectAnswer(<?= $resposta->id ?>)">
                    <strong><?= Html::encode($resposta->texto) ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" class="btn btn-primary mt-3"
            id="submitBtn" onclick="submitAnswer()" disabled>
        Submeter Resposta
    </button>

    <?php ActiveForm::end(); ?>
</div>

<div class="mt-3 text-center" id="continueBtnContainer" style="display:none;">
    <button class="btn btn-success" onclick="goToNext()">Continuar</button>
</div>

<script>
    let selectedAnswerId = null;
    let isAnswered = false;
    const corretaId = <?= $corretaId ?>;

    function selectAnswer(id) {
        if (isAnswered) return;

        selectedAnswerId = id;
        document.getElementById('resposta_id_input').value = id;

        document.querySelectorAll('.resposta-card')
            .forEach(card => card.classList.remove('bg-warning'));

        document.getElementById('card-resposta-' + id)
            .classList.add('bg-warning');

        document.getElementById('submitBtn').disabled = false;
    }

    function submitAnswer() {
        if (!selectedAnswerId) return;

        const card = document.getElementById('card-resposta-' + selectedAnswerId);

        if (selectedAnswerId === corretaId) {
            card.classList.add('bg-success', 'text-white');
        } else {
            card.classList.add('bg-danger', 'text-white');
        }

        document.querySelectorAll('.resposta-card')
            .forEach(card => card.style.pointerEvents = 'none');

        document.getElementById('submitBtn').textContent = 'Resposta Submetida';
        document.getElementById('submitBtn').disabled = true;

        document.getElementById('continueBtnContainer').style.display = 'block';
        isAnswered = true;

        document.getElementById('respostaForm').submit();
    }

    function goToNext() {
        <?php if ($isUltimaPergunta): ?>
        window.location.href = "<?= Url::to(['finish', 'tentativa_id' => $tentativa->id]) ?>";
        <?php else: ?>
        window.location.href = "<?= Url::to(['pergunta', 'tentativa_id' => $tentativa->id, 'pergunta_id' => $proximaPerguntaId]) ?>";
        <?php endif; ?>
    }
</script>

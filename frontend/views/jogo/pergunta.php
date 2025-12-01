<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var common\models\Tentativa $tentativa */
/** @var common\models\Pergunta $pergunta */
/** @var common\models\Resposta[] $respostas */
/** @var bool $isUltimaPergunta */
/** @var int|null $proximaPerguntaId */
/** @var int $numeroAtual */
/** @var int $totalPerguntas */

$this->title = 'Pergunta do Quiz';
$this->RegisterCssFile("@web/css/pergunta.css");

$correta = $pergunta->respostaCorreta;
$corretaId = $correta ? $correta->id : 'null';
$tempoLimite = (int)$pergunta->tempolimite;
$pontosPergunta = (int)$pergunta->pontosvalor;
?>

<div class="card bg-body p-4 mt-5"
     style="max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem;">

    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <div class="progress my-progress w-100" style="height: 1.5rem; border-radius: 0.75rem;">
            <div class="progress-bar bg-primary" role="progressbar"
                 style="width: 0%; transition: width 0.8s;">
                0 / <?= $totalPerguntas ?>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; position: relative;">
            <div class="badge bg-primary score-box"
                 style="font-size: 1.25rem; padding: 0.75rem 1rem; position: relative;">
                Pontos: <span id="pontosTotais"><?= $tentativa->getPontosAteAgora() ?></span>
                <span id="pontosGanhos" style="position: absolute; bottom: -1.5rem; left: 50%; transform: translateX(-50%);
            font-size: 1rem; color: #28a745; opacity: 0; transition: all 0.8s;">+0</span>
            </div>
        </div>
    </div>

    <h2 class="text-primary mb-3 text-center">
        <?= Html::encode($pergunta->texto) ?>
    </h2>

    <div id="timerCircle">
        <div id="timerText" class="text-secondary"><?= $tempoLimite ?></div>
    </div>

    <?php $form = ActiveForm::begin([
            'id' => 'respostaForm',
            'method' => 'post',
            'action' => Url::to(['jogo/pergunta', 'tentativa_id' => $tentativa->id, 'pergunta_id' => $pergunta->id])
    ]); ?>

    <?= Html::hiddenInput('resposta_id', '', ['id' => 'resposta_id_input']); ?>

    <div class="row g-3">
        <?php foreach ($respostas as $resposta): ?>
            <div class="col-md-6">
                <div class="card resposta-card p-3 mb-3 border cursor-pointer"
                     id="card-resposta-<?= $resposta->id ?>"
                     data-resposta-id="<?= $resposta->id ?>"
                     style="transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;"
                     onmouseover="this.classList.add('border-primary', 'shadow-lg'); this.style.transform='scale(1.03)';"
                     onmouseout="this.classList.remove('border-primary', 'shadow-lg'); this.style.transform='scale(1)';"
                     onclick="selectAnswer(<?= $resposta->id ?>)">
                    <strong><?= Html::encode($resposta->texto) ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" class="btn btn-primary mt-3"
            id="submitBtn" onclick="enviarResposta()" disabled>
        Submeter Resposta
    </button>

    <?php ActiveForm::end(); ?>
</div>

<script>
    let selectedAnswerId = null;
    let isAnswered = false;
    let tempo = <?= $tempoLimite ?>;
    const corretaId = <?= $corretaId ?>;
    const pontosPergunta = <?= $pontosPergunta ?>;
    const timerText = document.getElementById('timerText');

    let contador = setInterval(() => {
        tempo--;
        timerText.textContent = tempo;

        if (tempo <= 0) {
            clearInterval(contador);
            submeterSemResposta();
        }
    }, 1000);

    function selectAnswer(id) {
        if (isAnswered) return;

        selectedAnswerId = id;
        document.getElementById('resposta_id_input').value = id;

        document.querySelectorAll('.resposta-card').forEach(card => {
            card.classList.remove('bg-secondary');
            card.classList.remove('text-primary');
        });

        const selectedCard = document.getElementById('card-resposta-' + id);
        selectedCard.classList.add('bg-secondary');
        const strong = selectedCard.querySelector('strong');
        if (strong) strong.classList.add('text-primary');

        document.getElementById('submitBtn').disabled = false;
    }

    function bloquearRespostas() {
        document.querySelectorAll('.resposta-card')
            .forEach(card => card.style.pointerEvents = 'none');
    }

    function enviarResposta() {
        if (!selectedAnswerId) return;

        isAnswered = true;
        clearInterval(contador);

        marcarResposta(selectedAnswerId === corretaId);

        bloquearRespostas();

        document.getElementById('submitBtn').textContent = 'Resposta Submetida';
        document.getElementById('submitBtn').disabled = true;


        setTimeout(() => {
            document.getElementById('respostaForm').submit();
        }, 1500);
    }

    function submeterSemResposta() {
        if (isAnswered) return;

        isAnswered = true;
        bloquearRespostas();

        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').textContent = 'Tempo Esgotado';

        const todasRespostas = Array.from(document.querySelectorAll('.resposta-card'));
        const respostasErradas = todasRespostas.filter(card => parseInt(card.dataset.respostaId) !== corretaId);

        if (respostasErradas.length > 0) {
            const escolhida = respostasErradas[Math.floor(Math.random() * respostasErradas.length)];
            selectedAnswerId = parseInt(escolhida.dataset.respostaId);

            escolhida.classList.add('bg-danger', 'text-white');
        }

        const corretaCard = document.getElementById('card-resposta-' + corretaId);
        if (corretaCard) corretaCard.classList.add('bg-success', 'text-white');

        atualizarProgressBar(<?= $numeroAtual ?> + 1, <?= $totalPerguntas ?>);

        setTimeout(() => {
            document.getElementById('resposta_id_input').value = selectedAnswerId;
            document.getElementById('respostaForm').submit();
        }, 1000);
    }

    function animarPontos(pontos) {
        const pontosTotais = document.getElementById('pontosTotais');
        const pontosGanhos = document.getElementById('pontosGanhos');

        let atual = parseInt(pontosTotais.textContent);
        const target = atual + pontos;
        let incremento = Math.ceil(pontos / 20);
        if (incremento < 1) incremento = 1;

        pontosGanhos.textContent = '+' + pontos;
        pontosGanhos.style.opacity = 1;
        pontosGanhos.style.transform = 'translateX(-50%) translateY(-1rem)';

        const interval = setInterval(() => {
            atual += incremento;
            if (atual >= target) {
                atual = target;
                clearInterval(interval);

                setTimeout(() => {
                    pontosGanhos.style.opacity = 0;
                    pontosGanhos.style.transform = 'translateX(-50%) translateY(0)';
                }, 800);
            }
            pontosTotais.textContent = atual;
        }, 30);
    }

    function marcarResposta(correta) {
        if (selectedAnswerId) {
            const card = document.getElementById('card-resposta-' + selectedAnswerId);
            const strong = card.querySelector('strong');

            if (strong) strong.classList.remove('text-primary');

            if (correta) {
                card.classList.add('bg-success', 'text-white');
                animarPontos(pontosPergunta);
            } else {
                card.classList.add('bg-danger', 'text-white');

                const corretaCard = document.getElementById('card-resposta-' + corretaId);
                if (corretaCard) {
                    const strongCorreta = corretaCard.querySelector('strong');
                    if (strongCorreta) strongCorreta.classList.remove('text-primary');
                    corretaCard.classList.add('bg-success', 'text-white');
                }
            }
        } else {
            const corretaCard = document.getElementById('card-resposta-' + corretaId);
            if (corretaCard) {
                const strongCorreta = corretaCard.querySelector('strong');
                if (strongCorreta) strongCorreta.classList.remove('text-primary');
                corretaCard.classList.add('bg-success', 'text-white');
            }
        }
    }


    function atualizarProgressBar(numeroAtual, totalPerguntas) {
        const progressBar = document.querySelector('.progress-bar');
        const percent = (numeroAtual / totalPerguntas) * 100;
        progressBar.style.width = percent + '%';
        progressBar.textContent = numeroAtual + ' / ' + totalPerguntas;
    }

    document.addEventListener('DOMContentLoaded', function () {
        atualizarProgressBar(<?= $numeroAtual ?>, <?= $totalPerguntas ?>);
    });

</script>

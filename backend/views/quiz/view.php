<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<h1><?= htmlspecialchars($jogo->titulo) ?></h1>

<p><strong>Descrição:</strong> <?= htmlspecialchars($jogo->descricao) ?></p>
<p><strong>Autor:</strong> <?= $jogo->autor ? htmlspecialchars($jogo->autor->username) : 'Sem autor' ?></p>
<p><strong>Data de Criação:</strong> <?= Yii::$app->formatter->asDatetime($jogo->datacriacao, 'php:d/m/Y H:i') ?></p>
<p><strong>Status:</strong> <?= $jogo->IsPublic ? 'Público' : 'Privado' ?></p>

<hr>

<h3>Perguntas do Jogo</h3>

<?php if (empty($jogo->perguntas)): ?>
    <p>Nenhuma pergunta cadastrada.</p>
<?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Pergunta</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($jogo->perguntas as $pergunta): ?>
            <tr>
                <td><?= htmlspecialchars($pergunta->texto) ?></td>
                <td>
                    <a href="<?= Url::to(['pergunta/update', 'id' => $pergunta->id]) ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <a href="<?= Url::to(['pergunta/delete', 'id' => $pergunta->id]) ?>" class="btn btn-sm btn-danger"
                       data-confirm="Tem certeza que deseja apagar?" data-method="post">Apagar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Botão “Adicionar Pergunta” sempre visível -->
<a href="<?= Url::to(['pergunta/create', 'jogo_id' => $jogo->id]) ?>" class="btn btn-success mt-2">
    Adicionar Pergunta
</a>

<hr>

<div class="d-flex gap-2 mt-3">
    <a href="<?= Url::to(['jogo/delete', 'id' => $jogo->id]) ?>" class="btn btn-danger"
       data-confirm="Tem certeza que deseja apagar o jogo?" data-method="post">Eliminar Jogo</a>
    <a href="<?= Url::to(['quiz/index']) ?>" class="btn btn-secondary">Voltar</a>
</div>

<h1 class="mb-4">Todos os Jogos</h1>

<form method="get" style="margin-bottom: 20px; width: 320px;">
    <select name="filtroOrdenar" class="form-control" onchange="this.form.submit()">
        <option value="">Ordenar Por</option>
        <option value="todos_desc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'todos_desc') ? 'selected' : '' ?>>
            Todos - Mais recentes
        </option>
        <option value="todos_asc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'todos_asc') ? 'selected' : '' ?>>
            Todos - Mais antigos
        </option>
        <option value="publicos_desc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'publicos_desc') ? 'selected' : '' ?>>
            Públicos - Mais recentes
        </option>
        <option value="publicos_asc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'publicos_asc') ? 'selected' : '' ?>>
            Públicos - Mais antigos
        </option>
        <option value="privados_desc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'privados_desc') ? 'selected' : '' ?>>
            Privados - Mais recentes
        </option>
        <option value="privados_asc" <?= (isset($filtroOrdenar) && $filtroOrdenar == 'privados_asc') ? 'selected' : '' ?>>
            Privados - Mais antigos
        </option>
    </select>
</form>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Data de Criação</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($jogos)): ?>
        <tr><td colspan="5" class="text-center">Nenhum jogo encontrado</td></tr>
    <?php else: ?>
        <?php foreach ($jogos as $jogo): ?>
            <tr>
                <td><?= htmlspecialchars($jogo->titulo) ?></td>
                <td><?= $jogo->autor ? htmlspecialchars($jogo->autor->username) : 'Sem autor' ?></td>
                <td><?= Yii::$app->formatter->asDatetime($jogo->datacriacao, 'php:d/m/Y H:i') ?></td>
                <td>
                    <?= $jogo->IsPublic ? '<span class="text-success">Público</span>' : '<span class="text-danger">Privado</span>' ?>
                </td>
                <td>
                    <a href="<?= Yii::$app->urlManager->createUrl(['quiz/view', 'id'=>$jogo->id]) ?>" class="btn btn-sm btn-success">
                        Gerir
                    </a>

                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>



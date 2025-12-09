<?php

use common\models\Resposta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Respostas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resposta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Resposta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'texto',
                    'correta',
                    'pergunta_id',
                    [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Resposta $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                    ],
            ],
    ]); ?>


</div>

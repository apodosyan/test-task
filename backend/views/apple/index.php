<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Apple;

/* @var $this yii\web\View */
/* @var $statusLabels array */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Яблоки';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="apple-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Сгенерировать яблоки', ['generate'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Удалить все', ['remove-all'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Удалить все?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'color',
                    'format' => 'raw',
                    'value' => function ($model) {
                        [$gradient, $color] = $model->gradientAndColor;
                        return "<span class='apple' style='background: $color'></span>
                        <pie title='$model->size%' style=' background-color: $color; border: 2px solid $color; background-image:conic-gradient($gradient)'></pie>";
                    },
                ],
                [
                    'attribute' => 'appearance_date',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s', $model->appearance_date);
                    },
                ],
                [
                    'attribute' => 'fall_date',
                    'value' => function ($model) {
                        return $model->fall_date ? date('Y-m-d H:i:s', $model->fall_date) : 'Пока висит на дереве';
                    },
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) use ($statusLabels) {
                        if ($model->isRotted) {
                            $model->status = Apple::STATUS_ROTTEN;
                        }
                        return $statusLabels[$model->status];
                    },
                ],

                [
                    'format' => 'raw',
                    'label' => 'Действия',
                    'value' => function ($model) {
                        return Html::a('упасть', ['fall', 'id' => $model->id],
                                ['class' => 'btn btn-primary']) . '<br><br>' .
                            Html::button('съесть', [
                                'class' => 'btn btn-success eat-apple',
                                'data-id' => $model->id,
                                'data-size' => $model->size,
                                'data-alert' => $model->alertMessage,
                            ]);
                    },
                ],
            ],
        ]); ?>


    </div>
<?= $this->render('_modal'); ?>
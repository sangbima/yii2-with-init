<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <div class="card">
        <h5 class="card-header bg-info text-white">User List</h5>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'email:email',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '100px'],
                        'contentOptions' => ['class' => 'text-center'],
                        'filter' => Html::activeDropDownList($searchModel, 'status', [10 => 'Active', 9 => 'Banned'], ['prompt' => 'Status', 'class' => 'form-control']),
                        'value' => function ($model) {
                            return '' . $model->status == 10 ? '<span class="badge badge-success"><i class="fas fa-check"></i></span>' : '<span class="badge badge-danger"><i class="fas fa-ban"></i></span>';
                        }
                    ],

                    [
                        'class' => 'app\grid\ActionColumn',
                        'headerOptions' => ['width' => '100px'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <br />
    <div class="card">
        <h5 class="card-header bg-warning text-light">Trash</h5>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderTrash,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'email:email',
                    [
                        'class' => 'app\grid\ActionColumn',
                        'headerOptions' => ['width' => '100px'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{restore} {delete}',
                        'buttons' => [
                            'restore' => function ($url) {
                                return Html::a('<i class="fas fa-reply"></i>', $url, [
                                    'title' => 'Restore',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Are you sure you want to restore this item?'
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-trash"</i>',
                                    Url::to(['/user/force-delete', 'id' => $model->id]),
                                    [
                                        'title' => 'Force Delete',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure you want to delete this item permanently?'
                                    ]
                                );
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
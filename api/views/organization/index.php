<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel api\models\OrganizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '组织管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增组织', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id'=>'org-data']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'org-data',
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'enname',
            //'eccount',
            [
                'label'=>'创建人',
                'attribute' => 'username',
                'value' => 'user.username',
            ],
            [
                'attribute' => 'createtime',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'isdelete',
                'value'=>function ($model,$key,$index,$column){
                        return $model->isdelete?'禁用':'正常';
                    },
                 'filter' => ['1'=>'禁用','0'=>'正常'],
             ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {update}'
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>


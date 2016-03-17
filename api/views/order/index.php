<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel api\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '充值管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <!--  <p>
        <?/*= Html::a('充值', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?php Pjax::begin(['id' => 'countries']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'oid',
            'appkey',
            //'orgid',
            //'createuserid',
            'out_trade_no',
            'paysum',
            'paynum',
            //'status',
            //'statusname',
            [
               'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($data) {
                     return \common\helpers\BDefind::getValue(\api\models\Order::$STATUS,$data->status);
                },
                'filter' => \api\models\Order::$STATUS
            ],
            'createtime',
            // 'post1:ntext',
            // 'post2:ntext',
            // 'posttime',
            // 'result:ntext',
            // 'resulttime',
            // 'callback:ntext',
            // 'callbacktime',

           /* ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'10%'],
                'contentOptions' => ['style' => 'text-align:center'],
                'template' => '{view} {update} {delete} ',//{user-pay}
                'buttons' => [
                    'user-pay' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Pay'),
                            'aria-label' => Yii::t('yii', 'Pay'),
                            'data-pjax' => '0',
                            'target'=>'_blank',
                        ];
                        $url = "http://a.btgerp.com/pay?orgid=".$model->orgid;
                        return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url, $options);
                    }]
            ]*/
        ],
    ]); ?>

    <?php Pjax::end() ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel api\models\AccessAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '应用管理';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="access-app-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= Html::a('创建应用', ['create'], ['class' => 'btn btn-success rb-app-create']) ?>

    <?php Pjax::begin(['id'=>'apps']) ?>

    <?= Html::a('重新加载', ['index'], ['class' => 'btn btn-info']) ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'appkey',
            'appname',
            'client_id',
            //'client_secret',
            'eccount',
            'created',
            // 'modified',

            //['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'10%'],
                'contentOptions' => ['style' => 'text-align:center'],
                'template' => '{view} {update} {delete} {user-pay}',
                'buttons' => [
                    'user-pay' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Pay'),
                            'aria-label' => Yii::t('yii', 'Pay'),
                            //'data-pjax' => '0',
                            //'data-method' => 'dotest()',
                            //'data-confirm' => '我去',
                            'data-appkey' => $model->appkey,
                            'target'=>'_blank',
                            'class' => 'link-pay'
                        ];
                        $url = "/pay?appkey=".$model->appkey;
                        return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url, $options);
                    }]
            ]
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>


<div class="modal fade rb-btn-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">支付情况</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <?php echo $this->render('_form', ['model' => $model]); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary rb-modal-pay-save">确定</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        $('.rb-reload').on('click',function(e){
            e.preventDefault();

            console.info(e);

            $.pjax.reload({container:"#apps"});
            return false;
        });

        $('.rb-app-create').on('click',function(e){
            e.preventDefault();

            $('.rb-btn-model').modal('toggle');

        });

        $('.rb-modal-pay-save').on('click',function(e){
            console.info(e);

            $('.rb-modal-save-btn').click();
            //$.pjax.submit(e,'#apps');
        });

        var container = $("#new-app");//容器
        container.on('pjax:beforeSend',function(args){
            //ajax请求之前调用，返回false中断ajax请求
        })
        container.on('pjax:error',function(args){
            //ajax请求失败之后调用
        })
        container.on('pjax:success',function(args){
            //ajax请求成功之后调用
            console.info(args);
            var text = args.delegateTarget.textContent;
            console.info(text);
            var obj = $.parseJSON(text);
            console.info(obj.status);
        })

    });
</script>

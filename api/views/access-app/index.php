<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\helpers\BDataHelper;

/* @var $this yii\web\View */
/* @var $searchModel api\models\AccessAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '应用管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-app-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(BDataHelper::check_access('/access-app/create')){?>
        <p>
            <?= Html::a('创建应用', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php }?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                'headerOptions' => ['width'=>'8%','style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
                /*'template' => '{view} {update} {delete} {user-pay}',*/
                'template' => '{update}{user-pay}',
                'buttons' => [
                    'update'=>function($url,$model){
                            if(BDataHelper::check_access('/access-app/update')){
                                $url = "/access-app/update?id=".$model->appkey;
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, array());
                            }
                        },
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

</div>


<div class="modal fade rb-btn-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">支付情况</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid rb-app-pay">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary rb-modal-pay-save">确定</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade rb-btn-result-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="hidden" aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">支付情况</h4>
            </div>
            <div class="modal-body" style="min-height: 220px">

                <div class="container-fluid rb-result-1">
                    <a class="btn btn-primary btn-lg rb-btn-pay" target="_blank" href="#">
                        <span class="rb-btn-pay-span">支付宝支付</span>
                    </a>
                </div>

                <div class="container-fluid hidden rb-result-2">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg rb-pay-success">支付成功</button>
                        </div>
                        <div class="col-lg-6 col-sm-6 text-center">
                            <button type="button" class="btn btn-warning btn-lg rb-pay-fail">支付失败</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){

        $('.link-pay').on('click',function(e){
            e.preventDefault();

            $('.rb-app-pay').load('/order/pay?appkey='+$(this).data('appkey'));

            var modal = $('.rb-btn-model');
            $(modal).modal('toggle');
            return false;
        });

        $('.rb-modal-pay-save').on('click',function(e){//确定
            e.preventDefault();

            var modal = $(this).closest('.modal');
            var form = modal.find('form');
            var num = form.find('#order-paynum').val();
            if($.hlt.empty(num) || !$.isNumeric(num)){
                alert('请正确输入充值次数');
                return false;
            }

            //发送请求
            jQuery.ajax({'type':'POST',
                'url':$(form).attr('action'),
                'cache':false,
                'dataType':'json',
                'data': $(form).serialize(),
                'async':false,
                'success':function(data){
                    console.info(data);
                    if(data.status == 'successful'){
                        console.info(data.messages);

                        //$(modal).modal('toggle');
                        //$('.rb-btn-result-model').modal('toggle');

                        var order = data.data;

                        $result = jQuery.getJSON('/pay/pay-url?appkey=' + order.appkey +'&out_trade_no='+ order.out_trade_no +'&payout=' + order.paysum,function($result){
                            var url = $result.data.cashier_url;
                            console.info(url);
                            $('.rb-btn-pay').attr('href',url);
                            $('.rb-btn-model').modal('toggle');
                            $('.rb-btn-result-model').modal('toggle');
                        });

                        return false;
                    }else{
                        console.info('ok');
                    }
                },
                'error':function(xhr,err,obj){
                    console.info(err);
                }
            });


        });


        $('.rb-btn-pay').on('click',function(e){
            $('.rb-result-1').toggle();
            $('.rb-result-2').removeClass('hidden');
        });

        $('.rb-pay-success').on('click',function(e){
            //alert('支付成功');
            var modal = $('.rb-btn-result-model');
            $(modal).modal('toggle');

            window.location = window.location.href;

        });

        $('.rb-pay-fail').on('click',function(e){
            alert('支付失败,请重新进行支付');
            var modal = $('.rb-btn-result-model');
            $(modal).modal('toggle');

            //window.location = window.location.href;
        });
    });


</script>

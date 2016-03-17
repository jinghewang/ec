<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel api\models\AccessAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '应用管理';
$this->params['breadcrumbs'][] = $this->title;


$this->registerJs(
    '$("document").ready(function(){
        $("#new-app").on("pjax:end", function() {
            $.pjax.reload({container:"#apps"});  //Reload GridView
        });
    });'
);

?>
<div class="access-app-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id'=>'new-app','enablePushState'=>false])?>

    <?php echo $this->render('_form_index', ['model' => $model]); ?>

    <?php Pjax::end(); ?>

    <p>
        <?= Html::a('重新加载', ['index'], ['class' => 'btn btn-info rb-reload']) ?>
    </p>


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


<script>
    $(function(){

        $('.rb-reload').on('click',function(e){
            e.preventDefault();

            console.info(e);

            $.pjax.reload({container:"#apps"});
            return false;
        });

    });
</script>

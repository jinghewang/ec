<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Country', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id'=>'countries','enablePushState'=>false])?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'population',
            'createtime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>


<script>
    $(function(){
        $('#countrysearch-code').bind('keypress',function(event){
            if(event.keyCode == "13")
                $('.rb-search-btn').click();
        });

        $('.rb-search-btn').on('click',function(e){
           e.preventDefault();
           //----
           if($.trim($('#countrysearch-code').val()).length == 0){
               alert('请输入搜索内容');
               return false;
           }

           var form = $(this).closest('form');
           $.pjax.reload({container:"#countries",push:false,data:form.serialize(),url:"/country/index"});
           return false;
        });

        $('.rb-form-search').on('submit',function(e){
           return false;
        });
    });
</script>

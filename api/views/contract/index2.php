<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\BDefind;
use api\models\Contract;

/* @var $this yii\web\View */
/* @var $searchModel api\models\ContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '电子合同';
$this->params['breadcrumbs'][] = $this->title;

//11222
?>
<div class="contract-index">

   <!-- <h1><?/*= Html::encode($this->title) */?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Create Contract', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'contr_id',
            [
                'label'=>'流水号',
                'format'=>'raw',
                'value' => function($data){
                    return $data->contr_id;
                },
                'headerOptions' => ['width'=>'10%'],
            ],
            //'contr_no',
            [
                'label'=>'合同号',
                'format'=>'raw',
                'value' => function($data){
                    $status=BDefind::getValue(Contract::$contract_status,$data->status);
                    return $data->contr_no."[{$status}]";
                },
                'headerOptions' => ['width'=>'40%'],
                'contentOptions' => ['style'=>'word-break:break-all']
            ],
            [
                'label'=>'签字',
                'format'=>'raw',
                'value' => function($data){
                    $url = "/contract-sign/sign?cno={$data->contr_id}";
                    $result = Html::a('签字', $url);
                    if($data->status!=Contract::CONTRACT_STATUS_SIGNIN){
                        return BDefind::getValue(Contract::$contract_status,$data->status);
                    }
                    return $result;
                }
            ],
            [
                'label'=>'查看',
                'format'=>'raw',
                'value' => function($data){
                    $url = "/contract-version/pdf?ecno={$data->contr_no}";
                    $result=  Html::a('PDF', $url);
                    $result .= Html::tag('br');
                    $url = "/contract-version/preview-tpl?ecno={$data->contr_no}&p=0";
                    $result .= Html::a('HTML', $url);
                    return $result;
                }
            ],
            //'vercode',
            //'type',
            //'is_lock',
            // 'lock_time',
            // 'status',
            // 'audit_status',
            // 'is_submit',
            // 'sub_time',
            // 'sign_time',
            // 'price',
            // 'num',
            // 'transactor',
            // 'oldcontr',
            // 'orgid',
            // 'createtime',
            // 'userid',
            // 'modified',
            // 'extra_data:ntext',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

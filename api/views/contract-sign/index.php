<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\ContractSignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contract Signs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-sign-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contract Sign', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sign_id',
            'contr_id',
            'sign_name',
            'sign_userid',
            'sign_time:datetime',
            // 'sign_data:ntext',
            // 'sign_sign',
            // 'sign_file',
            // 'mobile',
            // 'code',
            // 'email:email',
            // 'openid',
            // 'bindtime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

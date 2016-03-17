<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\Order;
use \common\helpers\BDataHelper;

/**
 * OrderSearch represents the model behind the search form about `api\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oid', 'createuserid', 'paynum', 'status'], 'integer'],
            [['appkey', 'orgid', 'createtime', 'out_trade_no', 'post1', 'post2', 'posttime', 'result', 'resulttime', 'callback', 'callbacktime'], 'safe'],
            [['paysum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if(BDataHelper::check_only_role()){
            $app=AccessApp::findOne(['uid'=>BDataHelper::getCurrentUserId()]);
            $query =Order::find()->where(['appkey'=>$app->appkey]);
        }else{
            $query = Order::find();;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'oid' => $this->oid,
            'createuserid' => $this->createuserid,
            'createtime' => $this->createtime,
            'paysum' => $this->paysum,
            'paynum' => $this->paynum,
            'status' => $this->status,
            'posttime' => $this->posttime,
            'resulttime' => $this->resulttime,
            'callbacktime' => $this->callbacktime,
        ]);

        $query->andFilterWhere(['like', 'appkey', $this->appkey])
            ->andFilterWhere(['like', 'orgid', $this->orgid])
            ->andFilterWhere(['like', 'out_trade_no', $this->out_trade_no])
            ->andFilterWhere(['like', 'post1', $this->post1])
            ->andFilterWhere(['like', 'post2', $this->post2])
            ->andFilterWhere(['like', 'result', $this->result])
            ->andFilterWhere(['like', 'callback', $this->callback]);

        return $dataProvider;
    }
}

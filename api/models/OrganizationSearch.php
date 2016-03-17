<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\Organization;

/**
 * OrganizationSearch represents the model behind the search form about `api\models\Organization`.
 */
class OrganizationSearch extends Organization
{
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orgid', 'name', 'parentid', 'enname', 'vercode', 'seal', 'logo', 'teltext', 'createuserid', 'createtime', 'extra_data','username'], 'safe'],
            [['lft', 'rgt', 'level','eccount', 'isdelete'], 'integer'],
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
        $query = Organization::find();
        $query->joinWith(['user']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'enname', $this->enname])
        ->andFilterWhere(['like', 'createuserid', $this->createuserid])
        ->andFilterWhere(['like', 'user.username', $this->username])
        ->andFilterWhere([
            'isdelete' => $this->isdelete,
        ]);

        return $dataProvider;
    }
}

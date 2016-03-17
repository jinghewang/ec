<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "debug".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $createtime
 * @property integer $typeid
 */
class Debug extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'debug';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['createtime'], 'safe'],
            [['typeid'], 'integer'],
            [['name'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'content' => 'Content',
            'createtime' => 'Createtime',
            'typeid' => 'Typeid',
        ];
    }
}

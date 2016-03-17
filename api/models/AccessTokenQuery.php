<?php

namespace api\models;

/**
 * This is the ActiveQuery class for [[AccessToken]].
 *
 * @see AccessToken
 */
class AccessTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AccessToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
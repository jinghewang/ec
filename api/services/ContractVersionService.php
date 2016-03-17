<?php
namespace api\services;

use api\models\ContractVersion;
use common\helpers\DataHelper;
use yii\base\Exception;
use yii\base\Security;
use yii\helpers\VarDumper;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/21
 * Time: 23:05
 */
class ContractVersionService
{

    /**
     * 根据合同版本号取得合同模板
     * @param $vercode
     * @retun string
     */
    static function getContractTemplateByVercode($vercode){
        $version=ContractVersion::findOne($vercode);
        if(!empty($version)){
            return self::getContractTemplateByModel($version);
        }
        return '';
    }

    /**
     * 根据合同版本取得合同模板
     * @param $model
     * @return string
     */
    static function getContractTemplateByModel($model){
        return '@app/../'.$model->file->file->path;
    }
}
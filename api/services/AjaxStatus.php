<?php
namespace api\services;

/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2015/7/22
 * Time: 10:54
 */
class AjaxStatus
{
    const PROPERTY_STATUS = "status";
    const PROPERTY_MESSAGES = "messages";
    const PROPERTY_CODE = "code";
    const PROPERTY_DATA = "data";
    //
    const STATUS_FAILED = "failed";
    const STATUS_SUCCESSFUL = "successful";

    const CODE_OK = 200;
    const CODE_400 = 400;
    const CODE_403 = 403;
    const CODE_404 = 404;
    const CODE_503 = 503;
}
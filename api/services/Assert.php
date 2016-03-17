<?php
namespace api\services;

use api\services\IllegalArgumentException;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/22
 * Time: 11:07
 */
class Assert
{
    public static function hasText($text, $message)
    {
        if (empty($text)) {
            throw new IllegalArgumentException($message);
        }
    }

    public static function notNull($text, $message)
    {
        if (empty($text)) {
            throw new IllegalArgumentException($message);
        }
    }

    public static function isNull($text, $message)
    {
        if (empty($text)) {
            throw new IllegalArgumentException($message);
        }
    }
}
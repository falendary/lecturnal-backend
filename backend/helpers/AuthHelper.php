<?php

namespace backend\helpers;

use common\models\User;
use Yii;
use backend\helpers\HeaderHelper;

class AuthHelper
{

    public static function getUser()
    {
        $headerToken = HeaderHelper::getBearerToken();
        $user = User::findIdentityByAuthToken($headerToken);
        return $user;
    }

    public static function isAuth()
    {

        $headerToken = HeaderHelper::getBearerToken();

        if (isset($headerToken)) {
            return true;
        }

        return false;

    }

}
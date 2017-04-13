<?php

namespace backend\helpers;

use Yii;

class HeaderHelper {

    public static function getBearerToken() {

        $headers = Yii::$app->getRequest()->getHeaders();

        $token = explode('Bearer ', $headers["authorization"])[1];

        return $token;

    }

}
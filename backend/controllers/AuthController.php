<?php

namespace backend\controllers;

use backend\helpers\AuthHelper;
use backend\helpers\HeaderHelper;

use backend\models\AuthToken;
use common\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\Cookie;

class AuthController extends Controller
{

    public function actionLogin()
    {
        $request = Yii::$app->request;
        if ($request->isPost || $request->isAjax) {

            $request_body = $request->rawBody;
            $data = json_decode($request_body);

            $user = new User();

            if ($userInstance = $user->findOne(["username" => $data->username])) {

                if ($userInstance->validatePassword($data->password)) {

                    $token = new AuthToken();
                    $token->user_id = $userInstance->id;
                    $token->token = Yii::$app->security->generateRandomString();
                    $token->created_at = date('Y-m-d H:i:s');
                    $token->expires_at = date('Y-m-d H:i:s', time() + 86400 * 365);
                    $token->save();

                    Yii::$app->user->setIdentity($userInstance);

                    $this->cookie('token', $token->token, $token->expires_at);

                    return array('data' => array("authorization" => "success"));
                } else {
                    return array("errors" => array("authorization" => array("invalid credentials!")));
                }
            } else {
                return array("errors" => array("authorization" => array("invalid credentials")));
            }

        }

    }

    function cookie($name, $value = null, $expire = null)
    {
        if (false === $value)
            \Yii::$app->response->cookies->remove($name);
        elseif ($value == null) {
            return \Yii::$app->request->cookies->getValue($name);
        }
//        yii\web\Cookie::httpOnly = false;
        $options['name'] = $name;
        $options['value'] = $value;
        $options['expire'] =  time() + 86400 * 365;
        $options['httpOnly'] = false;
//        $options['domain'] = 'colledge.online';
        $cookie = new Cookie($options);

        \Yii::$app->response->cookies->add($cookie);
    }

    public function actionLogout()
    {

        $headerToken = HeaderHelper::getBearerToken();
        $user = User::findIdentityByAuthToken($headerToken);

        if (AuthHelper::isAuth()) {
            $authToken = new AuthToken();
            if ($authTokenInstance = $authToken::findOne(['token' => $headerToken, 'user_id' => $user->id])) {

                $authTokenInstance->delete();

            } else {
                return array("errors" => array("token" => $headerToken . " is not exist"));
            }
        } else {
            return array("errors" => array("authorization" => "not authorized"));
        }
    }

    public function actionSignup()
    {

        $request = Yii::$app->request;
        if ($request->isPost || $request->isAjax) {

            $request_body = $request->rawBody;
            $data = json_decode($request_body);

            $user = new User();

            if (!$user::findOne(["username" => $data->username])) {
                if (!$user::findOne(["email" => $data->email])) {

                    $user->username = $data->username;
                    $user->setPassword($data->password);
                    $user->email = $data->email;
                    $user->created_at = date('Y-m-d H:i:s');
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();

                    $token = new AuthToken();
                    $token->user_id = $user->id;
                    $token->token = Yii::$app->security->generateRandomString();
                    $token->created_at = date('Y-m-d H:i:s');
                    $token->expires_at = date('Y-m-d H:i:s', time() + 86400 * 365);
                    $token->save();

                    $this->cookie('token', $token->token, $token->expires_at);

                    unset($user->password_hash);
                    return $user;

                } else {
                    return array("errors" => array("email" => array("email already exists")));
                }
            } else {
                return array("errors" => array("username" => array("username already exists")));
            }

        } else {
            return array("errors" => array("method" => array("this http method is not supported")));
        }

    }

}
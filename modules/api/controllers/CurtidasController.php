<?php

namespace app\modules\api\controllers;
use yii\filters\auth\HttpBasicAuth;

class CurtidasController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Curtidas';

    public function behaviors()
    {
        $bahaviors = parent::behaviors();
        $bahaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth']
        ];
        return $bahaviors;
    }

    public function auth($username, $password)
    {
        $user = \app\models\User::findByUsername($username);

        if ($password && $user && $user->validatePassword($password)) {

            return $user;
        }
    }

}

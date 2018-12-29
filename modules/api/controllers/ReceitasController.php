<?php

namespace app\modules\api\controllers;

use yii\filters\auth\HttpBasicAuth;

/**
 * Default controller for the `api` module
 */
class ReceitasController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Receita';

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

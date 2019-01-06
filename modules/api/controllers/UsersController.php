<?php

namespace app\modules\api\controllers;


/**
 * Default controller for the `api` module
 */
class UsersController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionLogin(){
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');

        $user = \app\models\User::findByUsername($username);

        if ($password && $user && $user->validatePassword($password)) {
            return ['success'=>true, 'response'=>$user];
        }

        return ['success'=>false, 'response'=>"Unauthorized"];
    }

}

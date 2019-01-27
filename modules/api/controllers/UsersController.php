<?php

namespace app\modules\api\controllers;

use app\models\User;
use Yii;

/**
 * Default controller for the `api` module
 */
class UsersController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionLogin()
    {
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');

        $user = \app\models\User::findByUsername($username);

        if ($password && $user && $user->validatePassword($password)) {
            return ['success' => true, 'response' => $user];
        }

        return ['success' => false, 'response' => "Unauthorized"];
    }


    public function actionSignup()
    {
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');
        $email = \Yii::$app->request->post('email');
        if ($username && $password && $email) {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->created_at = time();
            $user->updated_at = time();
            $user->save(false);

            return $user;
        }
        return null;
    }

}

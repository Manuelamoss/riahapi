<?php

namespace app\modules\api\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii;
use app\models\Curtidas;
use app\models\Receita;

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

    public function actionCurtir($id)
    {
        $curtida = Curtidas::find()->where(['id_receita' => $id, 'id_user' => Yii::$app->user->id, 'status' => 1])->one();
        $data = Receita::find()->where(['id' => $id])->one();

        if (empty($curtida)) {
            $novaCurtida = new Curtidas();
            $novaCurtida->id_user = Yii::$app->user->id;
            $novaCurtida->id_receita = $id;
            $novaCurtida->status = 1;
            $novaCurtida->save();
            $data->curtir = $data->curtir + 1;
        } else {
            $curtida->delete();
            $data->curtir = $data->curtir - 1;
        }
        $data->save();
        return ['ret'=>'OK'];
    }

    public function actionDescurtir($id)
    {
        $curtida = Curtidas::find()->where(['id_receita' => $id, 'id_user' => Yii::$app->user->id, 'status' => -1])->one();
        $data = Receita::find()->where(['id' => $id])->one();

        if (empty($curtida)) {
            $novaCurtida = new Curtidas();
            $novaCurtida->id_user = Yii::$app->user->id;
            $novaCurtida->id_receita = $id;
            $novaCurtida->status = -1;
            $novaCurtida->save();
            $data->descurtir = $data->descurtir + 1;
        } else {
            $curtida->delete();
            $data->descurtir = $data->descurtir - 1;
        }
        $data->save();
        return ['ret'=>'OK'];
    }
}

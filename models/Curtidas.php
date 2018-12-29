<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curtidas".
 *
 * @property int $id
 * @property int $id_receita
 * @property int $id_user
 * @property int $status
 *
 * @property Receita $receita
 * @property User $user
 */
class Curtidas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curtidas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_receita', 'id_user', 'status'], 'required'],
            [['id_receita', 'id_user', 'status'], 'integer'],
            [['id_receita'], 'exist', 'skipOnError' => true, 'targetClass' => Receita::className(), 'targetAttribute' => ['id_receita' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_receita' => 'Id Receita',
            'id_user' => 'Id User',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceita()
    {
        return $this->hasOne(Receita::className(), ['id' => 'id_receita']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}

<?php

namespace app\models;


/**
 * This is the model class for table "receita".
 *
 * @property int $id
 * @property string $nome
 * @property string $tempo_preparo
 * @property string $descricao_preparo
 * @property int $id_categoria
 * @property int $curtir
 * @property int $descurtir
 *
 * @property Comentario[] $comentarios
 * @property Curtidas[] $curtidas
 * @property Categoria $categoria
 */
class Receita extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receita';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'tempo_preparo', 'descricao_preparo', 'id_categoria'], 'required'],
            [['descricao_preparo'], 'string'],
            [['id_categoria', 'curtir', 'descurtir'], 'integer'],
            [['nome', 'tempo_preparo'], 'string', 'max' => 20],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['id_categoria' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'tempo_preparo' => 'Tempo Preparo',
            'descricao_preparo' => 'Descricao Preparo',
            'categoria' => 'Categoria',
            'curtir' => 'Curtir',
            'descurtir' => 'Descurtir',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub

        //obter dados do registo em causa
        $myObj = new \stdClass();
        $myObj->id = $this->id;
        $myObj->nome = $this->nome;
        $myObj->tempo_preparo = $this->tempo_preparo;
        $myObj->descricao_preparo = $this->descricao_preparo;
        $myObj->id_categoria = $this->id_categoria;
        $myObj->curtir = $this->curtir;
        $myObj->descurtir = $this->descurtir;

        $myJSON = json_encode($myObj);

        if ($insert) {
            $this->FazPublish("INSERT", $myJSON); //notificação a nivel da aplicação
            $this->FazPublish("NEWS", "Uma nova receita foi adicionada!"); //notificação a nivel do utilizador
        } else
            $this->FazPublish("UPDATE", $myJSON); //notificação a nivel da aplicação
    }

    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub

        $prod_id = $this->id;
        $myObj = new \stdClass();
        $myObj->id = $prod_id;
        $myJSON = json_encode($myObj);

        $this->FazPublish("DELETE", $myJSON);//notificação a nivel da aplicação
    }

    public function FazPublish($canal, $msg)
    {

        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; //set your username
        $password = ""; //set your password
        $client_id = "phpMQTT-publisher"; //unique!
        $mqtt = new \app\mosquitto\phpMQTT($server, $port, $client_id);
        try {
            if ($mqtt->connect(true, NULL, $username, $password)) {
                $mqtt->publish($canal, $msg, 0);
                $mqtt->close();
            } else {

                file_put_contents("debug.output", "Time out!");
            }
        } catch (\Exception $e) { //tratar a exceção lançada quando não consegue conectar ao mosquitto.

        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentario::className(), ['id_receita' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurtidas()
    {
        return $this->hasMany(Curtidas::className(), ['id_receita' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['id' => 'id_categoria']);
    }
}

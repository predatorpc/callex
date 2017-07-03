<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsersClients;

/**
 * UsersClientsSearch represents the model behind the search form of `app\models\UsersClients`.
 */
class UsersClientsSearch extends UsersClients
{
    public $call_status_id;
    public $client_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'user_id', 'status'], 'integer'],
            [['call_status_id','client_name'],'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UsersClients::find()
            ->leftJoin('clients', '`users_clients`.`client_id` = `clients`.`id`');

        $query->where(['users_clients.user_id'=>Yii::$app->user->getId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(isset($this->call_status_id) && $this->call_status_id>0){
            $query->andWhere(['clients.call_status_id'=>$this->call_status_id]);
        }
        if(isset($this->client_name) && !empty($this->client_name)){
            $query->andWhere(['LIKE','clients.last_name',$this->client_name]);
        }
        if(isset($params['dateStart']) && strtotime($params['dateStart'])>0){
            $query->andWhere(['>=','users_clients.date',date('Y-m-d 00:00:00',strtotime($params['dateStart']))]);
        }
        if(isset($params['dateEnd']) && strtotime($params['dateEnd'])>0){
            $query->andWhere(['<=','users_clients.date',date('Y-m-d 23:59:59',strtotime($params['dateEnd']))]);
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            //'user_id' => $this->user_id,
            'date' => $this->date,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}

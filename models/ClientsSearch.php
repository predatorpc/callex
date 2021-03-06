<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clients;

/**
 * ClientsSearch represents the model behind the search form of `app\models\Clients`.
 */
class ClientsSearch extends Clients
{
    /**
     * @inheritdoc
     */
    public $count;
    public function rules()
    {
        return [
            [['id', 'gender', 'car', 'children', 'call_status_id', 'client_shop_id', 'client_helper_id', 'client_fit_id', 'status'], 'integer'],
            [['first_name', 'second_name', 'last_name', 'birthday', 'phone', 'district', 'date_create', 'date_update'], 'safe'],
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

        $query = Clients::find();

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
        $query->andWhere(['NOT IN','call_status_id',[0,1,5,6]]);
        if(isset($this->first_name) && !empty($this->first_name)){
            $query->orWhere(['LIKE','first_name',$this->first_name]);
            $query->orWhere(['LIKE','second_name',$this->first_name]);
            $query->orWhere(['LIKE','last_name',$this->first_name]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'car' => $this->car,
            'children' => $this->children,
            'call_status_id' => $this->call_status_id,
            'client_shop_id' => $this->client_shop_id,
            'client_helper_id' => $this->client_helper_id,
            'client_fit_id' => $this->client_fit_id,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'district', $this->district]);
            //->andFilterWhere(['like', 'first_name', $this->first_name])
            //->andFilterWhere(['like', 'second_name', $this->second_name])
            //->andFilterWhere(['like', 'last_name', $this->last_name])


        return $dataProvider;
    }

    public function searchPower($params)
    {

        $query = Clients::find()->select('count(clients.id) as count,users_clients.user_id as user_id')->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')->groupBy('users_clients.user_id');

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

        $query->andWhere(['NOT IN','call_status_id',[0,1,5,6]]);
        if(isset($params['dateStart'])){
            $dateStart = $params['dateStart'];
            $query->andWhere(['>=','users_clients.date',date('Y-m-d 00:00:00',strtotime($dateStart))]);
        }
        if(isset($params['dateEnd'])){
            $dateEnd = $params['dateEnd'];
            $query->andWhere(['<=','users_clients.date',date('Y-m-d 23:59:59',strtotime($dateEnd))]);
        }

        if(isset($this->first_name) && !empty($this->first_name)){
            $query->orWhere(['LIKE','first_name',$this->first_name]);
            $query->orWhere(['LIKE','second_name',$this->first_name]);
            $query->orWhere(['LIKE','last_name',$this->first_name]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'car' => $this->car,
            'children' => $this->children,
            'call_status_id' => $this->call_status_id,
            'client_shop_id' => $this->client_shop_id,
            'client_helper_id' => $this->client_helper_id,
            'client_fit_id' => $this->client_fit_id,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'district', $this->district]);
        //->andFilterWhere(['like', 'first_name', $this->first_name])
        //->andFilterWhere(['like', 'second_name', $this->second_name])
        //->andFilterWhere(['like', 'last_name', $this->last_name])


        return $dataProvider;
    }
}

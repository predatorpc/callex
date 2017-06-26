<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Comments;

/**
 * CommentsSearch represents the model behind the search form of `app\models\Comments`.
 */
class CommentsSearch extends Comments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'action_id', 'call_status_id', 'status'], 'integer'],
            [['client_id','created_by_user','phone'],'string'],
            [['text', 'date'], 'safe'],
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
        $query = Comments::find()->leftJoin('clients','clients.id = comments.client_id')->leftJoin('users','users.id = comments.created_by_user');

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
        if(isset($this->client_id) && !empty($this->client_id)){
            foreach (explode(' ',$this->client_id) as $key => $value) {
                $query->orWhere(['LIKE', 'clients.first_name', $value]);
                $query->orWhere(['LIKE', 'clients.second_name', $value]);
                $query->orWhere(['LIKE', 'clients.last_name', $value]);
            }
        }

        if(isset($this->created_by_user) && !empty($this->created_by_user)){
            foreach (explode(' ',$this->created_by_user) as $key => $value) {
                $query->orWhere(['LIKE', 'users.first_name', $value]);
                $query->orWhere(['LIKE', 'users.second_name', $value]);
                $query->orWhere(['LIKE', 'users.last_name', $value]);
            }
        }

        if(isset($this->phone) && !empty($this->phone)){
            $query->orWhere(['LIKE', 'clients.phone', $this->phone]);
        }

        if(isset($this->call_status_id) && !empty($this->call_status_id)){
            $query->orWhere(['clients.call_status_id'=>$this->call_status_id]);
        }
        if(isset($params['dateStart'])){
            echo 123456;
            $dateStart = $params['dateStart'];
            $query->andWhere(['>=','date',date('Y-m-d 00:00:00',strtotime($dateStart))]);
        }
        if(isset($params['dateEnd'])){
            $dateEnd = $params['dateEnd'];
            $query->andWhere(['<=','date',date('Y-m-d 23:59:59',strtotime($dateEnd))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'client_id' => $this->client_id,
            'type_id' => $this->type_id,
            'action_id' => $this->action_id,
            //'created_by_user' => $this->created_by_user,
            //'date' => $this->date,
            //'call_status_id' => $this->call_status_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}

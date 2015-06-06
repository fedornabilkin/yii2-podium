<?php

namespace bizley\podium\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\HtmlPurifier;

class SearchForm extends Model
{

    public $query;
    public $match;
    public $author;
    public $date_from;
    public $date_to;
    public $forums;
    public $type;
    public $display;

    public function rules()
    {
        return [
            [['query', 'author'], 'string'],
            [['query', 'author'], 'filter', 'filter' => function($value) {
                return HtmlPurifier::process($value);
            }],
            [['match'], 'in', 'range' => ['all', 'any']],
            [['date_from', 'date_to'], 'default', 'value' => null],
            [['date_from', 'date_to'], 'date'],
            [['forums'], 'safe'],
            [['type', 'display'], 'in', 'range' => ['posts', 'topics']],
        ];
    }

    public function searchAdvanced()
    {
        if ($this->type == 'topics') {
            $query = Thread::find()->where(['like', 'name', $this->query]);
        }
        else {
            $query = Vocabulary::find()->select('post_id, thread_id')->joinWith(['posts'])->where(['like', 'word', $this->query]);
        }       
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'defaultOrder' => ['thread_id' => SORT_DESC],
//                'attributes' => [
//                    'thread_id' => [
//                        'asc'     => ['thread_id' => SORT_ASC],
//                        'desc'    => ['thread_id' => SORT_DESC],
//                        'default' => SORT_DESC,
//                    ],
//                ]
//            ],
        ]);

        return $dataProvider;
    }
}
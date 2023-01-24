<?php

namespace app\models;
use yii\base\Model;

class Page extends Model
{
    public $content;
    public $title;
    public $description;
    public $keywords;
    public function rules()
    {
        return [
            [['content'], 'required'],
        ];
    }
}

<?php

namespace b4worldview\tractionms\models;

use craft\base\Model;
use craft\db\ActiveRecord;

class ShareReviewModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tractionms_appreviews}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['overall','favTrophy','suggestions'], 'required'],
        ];
    }

}
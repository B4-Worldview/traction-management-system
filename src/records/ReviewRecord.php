<?php

namespace b4worldview\tractionms\records;

use craft\db\ActiveRecord;

/**
 * ReviewRecord
 *
 * @property int $id
 * @property string $overall
 * @property string $favTrophy
 * @property string $suggestions
 */
class ReviewRecord extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tractionms_appreviews}}';
    }
    

}
<?php

namespace b4worldview\tractionms\records;

use craft\db\ActiveRecord;

/**
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property int $age
 * @property string $professionChristian
 * @property string $timezone
 */
class ProfileRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tractionms_profiles}}';
    }


    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'age', 'email'], 'required'],
            [['firstName', 'lastName'], 'string'],
            ['age', 'integer'],
            [['email'], 'email'],
        ];
    }

}
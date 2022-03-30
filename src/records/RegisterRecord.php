<?php

namespace b4worldview\tractionms\records;

use craft\db\ActiveRecord;

/**
 * @property int $profileId
 * @property string $registrationType
 */
class RegisterRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tractionms_registrations}}';
    }


    public function rules(): array
    {
        return [
            [['profileId', 'registrationType'], 'required'],
            [['profileId'], 'integer'],
            [['registrationType'], 'string']
        ];
    }

}
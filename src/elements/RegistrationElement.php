<?php

namespace b4worldview\tractionms\elements;

use b4worldview\tractionms\elements\db\RegistrationElementQuery;
use craft\elements\db\ElementQueryInterface;

class RegistrationElement extends \craft\base\Element
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Registration';
    }


    /**
     * @return ElementQueryInterface
     */
    public static function find(): ElementQueryInterface
    {
        return new RegistrationElementQuery(static::class);
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return 'Registrations';
    }

    /**
     * @var int
     */
    public int $group = 0;
    /**
     * @var int
     */


    public int $profile = 0;
    /**
     * @var string
     */


    public string $registrationType = "";

    /**
     * Hooks into the CraftCMS process after this element is saved in the Craft
     * elements table, this code is executed.
     *
     * @param bool $isNew
     * @return void
     * @throws \yii\db\Exception
     */
    public function afterSave(bool $isNew): void
    {
        if ($isNew) {
            \Craft::$app->db->createCommand()
                ->insert('{{%tractionms_registrations}}', [
                    'id' => $this->id,
                    'profile' => $this->profile,
                    'registrationType' => $this->registrationType
                ])
                ->execute();
        } else {
            \Craft::$app->db->createCommand()
                ->update('{{%tractionms_registrations}}', [
                    'profile' => $this->profile,
                    'registrationType' => $this->registrationType
                ], ['id' => $this->id])
                ->execute();
        }

        parent::afterSave($isNew);
    }
}
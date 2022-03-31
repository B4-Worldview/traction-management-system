<?php

namespace b4worldview\tractionms\elements;

use b4worldview\tractionms\elements\db\ProfileElementQuery;
use craft\elements\db\ElementQueryInterface;

class ProfileElement extends \craft\base\Element
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Profile';
    }


    /**
     * @return ElementQueryInterface
     */
    public static function find(): ElementQueryInterface
    {
        return new ProfileElementQuery(static::class);
    }


    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return 'Profiles';
    }


    /**
     * @var int
     */
    public int $age = 0;


    /**
     * @var string
     */
    public string $firstName = "";


    /**
     * @var string
     */
    public string $lastName = "";


    /**
     * @var string
     */
    public string $professingChristian = "";


    /**
     * @var string
     */
    public string $timezone = "";


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
                ->insert('{{%tractionms_profiles}}', [
                    'id' => $this->id,
                    'firstName' => $this->firstName,
                    'lastName' => $this->lastName,
                    'age' => $this->age,
                    'professingChristian' => $this->professingChristian,
                    'timezone' => $this->timezone,
                ])
                ->execute();
        } else {
            \Craft::$app->db->createCommand()
                ->update('{{%tractionms_registrations}}', [
                    'firstName' => $this->firstName,
                    'lastName' => $this->lastName,
                    'age' => $this->age,
                    'professingChristian' => $this->professingChristian,
                    'timezone' => $this->timezone,
                ], ['id' => $this->id])
                ->execute();
        }

        parent::afterSave($isNew);
    }


    /**
     * @return array
     */
    protected static function defineTableAttributes(): array
    {
        return [
            'firstName' => \Craft::t('tractionms', 'First Name'),
            'lastName' => \Craft::t('tractionms', 'Last Name'),
        ];
    }


    protected static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All Profiles',
                'criteria' => []
            ],
        ];
    }

}
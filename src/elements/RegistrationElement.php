<?php

namespace b4worldview\tractionms\elements;

use b4worldview\tractionms\elements\db\RegistrationElementQuery;
use craft\elements\db\ElementQueryInterface;

class RegistrationElement extends \craft\base\Element
{
    /**
     * @var int
     */
    public int $group = 0;
    /**
     * @var int
     */
    public int $profileId = 0;
    /**
     * @var string
     */
    public string $registrationType = "";

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Registration';
    }

    /**
     * @return RegistrationElementQuery
     */
    public static function find(): ElementQueryInterface
    {
        return new RegistrationElementQuery(static::class);
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public static function hasStatuses(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return 'Registrations';
    }

    /**
     * @inerhitDoc
     * @return array[]
     */
    public static function statuses(): array
    {
        return [
            'waiting' => ['label' => \Craft::t('tractionms', 'Waiting Assignment'), 'color' => 'ff932c'],
            'group' => ['label' => \Craft::t('tractionms', 'Group'), 'color' => '46ac4e'],
            'finished' => ['label' => \Craft::t('tractionms', 'Finished'), 'color' => 'ac0100'],
        ];
    }

    /**
     * @return array
     */
    protected static function defineTableAttributes(): array
    {
        return [
            'registrationType' => \Craft::t('tractionms', 'Type'),
            'profileId' => \Craft::t('tractionms', 'Name'),
        ];
    }

    protected static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All Registrations',
                'criteria' => []
            ],
        ];
    }


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
                    'profileId' => $this->profileId,
                    'registrationType' => $this->registrationType
                ])
                ->execute();
        } else {
            \Craft::$app->db->createCommand()
                ->update('{{%tractionms_registrations}}', [
                    'profileId' => $this->profileId,
                    'registrationType' => $this->registrationType
                ], ['id' => $this->id])
                ->execute();
        }

        parent::afterSave($isNew);
    }

    /**
     * @param string $status
     * @return bool[]
     */
    protected function statusCondition(string $status)
    {
        switch ($status) {
            case 'waiting':
                return ['waiting' => true];
            case 'group':
                return ['group' => true];
            case 'finished':
                return ['finished' => true];
            default:
                // call the base method for `enabled` or `disabled`
                return parent::statusCondition($status);
        }
    }

}
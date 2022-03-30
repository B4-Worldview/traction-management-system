<?php

namespace b4worldview\tractionms\elements;

use b4worldview\tractionms\elements\db\RegistrationElementQuery;

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
     * @return RegistrationElementQuery
     */
    public static function find(): RegistrationElementQuery
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
     * @inheritDoc
     * @return string
     */
    public function getStatus()
    {
        if ($this->groupIsTrue) {
            return 'group';
        }

        if ($this->finishedIsTrue) {
            return 'finished';
        }

        return 'waiting';
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
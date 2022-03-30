<?php

namespace b4worldview\tractionms\elements\db;

class RegistrationElementQuery extends \craft\elements\db\ElementQuery
{
    public int $profileId = 0;
    public string $registrationType = "";

    public function profileId($value): RegistrationElementQuery
    {
        $this->profileId = $value;
        return $this;
    }

    public function registrationType($value): RegistrationElementQuery
    {
        $this->registrationType = $value;
        return $this;
    }


    protected function beforePrepare(): bool
    {
        // join in the products table
        $this->joinElementTable('tractionms_registrations');

        // select the price and currency columns
        $this->query->select([
            'tractionms_registrations.profileId',
            'tractionms_registrations.registrationType'
        ]);

        if ($this->profileId) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_registrations.profileId', $this->profileId));
        }

        if ($this->registrationType) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_registrations.registrationType', $this->registrationType));
        }

        return parent::beforePrepare();
    }

}
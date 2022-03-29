<?php

namespace b4worldview\tractionms\elements\db;

class RegistrationElementQuery extends \craft\elements\db\ElementQuery
{
    public int $group;
    public int $profile;
    public string $registrationType;

    public function group($value): RegistrationElementQuery
    {
        $this->group = $value;
        return $this;
    }

    public function profile($value): RegistrationElementQuery
    {
        $this->profile = $value;
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
            'tractionms_registrations.group',
            'tractionms_registrations.profile',
            'tractionms_registrations.registrationType'
        ]);

        if ($this->group) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_registrations.group', $this->group));
        }

        if ($this->profile) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_registrations.profile', $this->profile));
        }

        if ($this->registrationType) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_registrations.registrationType', $this->registrationType));
        }

        return parent::beforePrepare();
    }

}
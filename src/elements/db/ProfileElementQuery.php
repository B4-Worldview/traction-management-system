<?php

namespace b4worldview\tractionms\elements\db;

class ProfileElementQuery extends \craft\elements\db\ElementQuery
{
    public int $age = 0;


    public string $firstName = "";


    public string $lastName = "";


    public string $professingChristian = "";


    public string $timezone = "";


    public function firstName($value): ProfileElementQuery
    {
        $this->firstName = $value;
        return $this;
    }


    public function lastName($value): ProfileElementQuery
    {
        $this->lastName = $value;
        return $this;
    }


    public function age($value): ProfileElementQuery
    {
        $this->age = $value;
        return $this;
    }


    /**
     * @param $value
     * @return $this
     */
    public function professingChristian($value): ProfileElementQuery
    {
        $this->professingChristian = $value;
        return $this;
    }


    /**
     * @param $value
     * @return $this
     */
    public function timezone($value): ProfileElementQuery
    {
        $this->timezone = $value;
        return $this;
    }


    /**
     * @return bool
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('tractionms_profiles');

        $this->query->select([
            'tractionms_profiles.firstName',
            'tractionms_profiles.lastName',
            'tractionms_profiles.age',
            'tractionms_profiles.professingChristian',
            'tractionms_profiles.timezone',
        ]);

        if ($this->firstName) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_profiles.firstName', $this->firstName));
        }

        if ($this->lastName) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_profiles.lastName', $this->lastName));
        }

        if ($this->age) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_profiles.age', $this->age));
        }

        if ($this->professingChristian) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_profiles.professingChristian', $this->professingChristian));
        }

        if ($this->timezone) {
            $this->subQuery->andWhere(Db::parseParam('tractionms_profiles.timezone', $this->timezone));
        }

        return parent::beforePrepare();
    }

}
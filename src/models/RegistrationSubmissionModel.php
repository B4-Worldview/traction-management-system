<?php

namespace b4worldview\tractionms\models;

class RegistrationSubmissionModel extends \craft\base\Model
{

    /**
     * @var int
     */
    public int $age;


    /**
     * @var string
     */
    public string $availableTimes = "";


    /**
     * @var string
     */
    public string $email;


    /**
     * @var string
     */
    public string $firstName;


    /**
     * @var string
     */
    public string $lastName;


    /**
     * @var string
     */
    public string $professingChristian = "";


    /**
     * @var string
     */
    public string $timezone = "";


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['age', 'firstName', 'lastName', 'email'], 'required'],
            [['age'], 'integer'],
            [['firstName', 'lastName', 'timezone', 'availableTimes', 'professingChristian'], 'string'],
            [['email'], 'email']
        ];
    }
}
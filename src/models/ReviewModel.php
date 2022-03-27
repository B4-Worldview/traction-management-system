<?php

namespace b4worldview\tractionms\models;

use craft\base\Model;
use craft\validators\DateTimeValidator;

class ReviewModel extends Model
{
    /**
     * @var string
     */
    public string $dateCreated = "";


    /**
     * @var string
     */
    public string $dateUpdated = "";


    /**
     * @var string
     */
    public string $favTrophy = "";


    /**
     * @var string
     */
    public string $overall = "";


    /**
     * @var string
     */
    public string $suggestions = "";


    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['overall', 'suggestions', 'favTrophy'], 'string'];
        $rules[] = [['dateCreated', 'dateUpdated'], DateTimeValidator::class];

        return $rules;
    } // rules
} // class
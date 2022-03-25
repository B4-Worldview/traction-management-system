<?php

namespace b4worldview\tractionms\models;

use craft\base\Model;

class SettingsModel extends Model
{
    /**
     * @var string
     */
    public string $contactUsEmail = "";


    /**
     * @var string
     */
    public string $leaveReviewEmail = "";


    /**
     * @var string
     */
    public string $demoEmail = "";

    /**
     * @var bool
     */
    public bool $demoLightSwitch = true;

}
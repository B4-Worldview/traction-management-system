<?php

namespace b4worldview\tractionms\services;

use craft\base\Component;

class RegistrationsService extends Component
{

    /**
     * Ben Croker copies an Active Record to a model with set attributes
     * SomeModel->setAttributes(SomeActiveRecord->getAttributes(), false)
     *
     * https://craftquest.io/courses/in-depth-on-craft-plugin-development/8399
     * 8 Minutes in
     *
     * Also:
     * https://craftquest.io/courses/in-depth-on-craft-plugin-development/9229
     * 9 Minutes in
     *
     * This enables us to set the actual attributes that we want to share with
     * the frontend
     *
     * Ben says there are 2 different types of data structures in Craft:
     * Models & Records
     * https://craftquest.io/courses/in-depth-on-craft-plugin-development/9229
     * at the beginning
     *
     *
     */


    /**
     * Ben Crocker Demonstrates events at:
     * https://craftquest.io/courses/in-depth-on-craft-plugin-development/9100
     * 7 Minutes in
     *
     * Yii2 Page:
     * https://www.yiiframework.com/doc/guide/2.0/en/concept-events
     *
     * Ben Croker says that checking for registered event handlers before running
     * them is often done in Craft and so he follows it.
     *
     */

// Constants
    /**
     * @event Event
     */
    public const EVENT_BEFORE_REGISTER_DISCIPLESHIP_GROUP = "beforeRegisterDiscipleshipGroup";

}
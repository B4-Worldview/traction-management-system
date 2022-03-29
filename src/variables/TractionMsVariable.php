<?php

namespace b4worldview\tractionms\variables;

use b4worldview\tractionms\elements\db\RegistrationElementQuery;
use b4worldview\tractionms\elements\RegistrationElement;


class TractionMsVariable
{

    /**
     * Twig uses magic getters.
     * See: https://craftquest.io/courses/in-depth-on-craft-plugin-development/8201
     */

    public function getRegistrations(): RegistrationElementQuery
    {
        return RegistrationElement::find();
    }
}
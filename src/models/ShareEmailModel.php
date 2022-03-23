<?php

namespace b4worldview\tractionms\models;

use craft\base\Model;

class ShareEmailModel extends Model
{
    /**
     * @var string|null
     */
    public string $name;

    /**
     * @var string|null
     */
    public string $email;

    /**
     * @var string|null
     */
    public string $message;

    /**
     * @inerhitDoc
     */
    protected function defineRules(): array
    {
        return [
            [['name','email', 'message'], 'required'],
            [['email'], 'email']
        ];
    }
}
<?php

namespace b4worldview\tractionms\controllers;

use Craft;
use craft\web\controller;
use yii\web\Response;
use craft\web\View;

use craft\db\Query;


class UserController extends controller {

    protected $allowAnonymous = true;

    public function actionRegister()
    {
        $variables = [];
        return $this->renderTemplate(
            'tractionms_fe/user/user_registration.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );
    } // public function actionRegister


}
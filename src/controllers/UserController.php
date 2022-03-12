<?php

namespace b4worldview\tractionms\controllers;

use Craft;
use craft\web\controller;
use yii\web\Response;
use craft\web\View;

use craft\db\Query;


class UserController extends controller {

    protected $allowAnonymous = true;

    public function actionProfile($status="") {

        $variables = [
            "status" => $status
        ];

        return $this->renderTemplate(
            'tractionms/user/user_profile.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );
    }

}
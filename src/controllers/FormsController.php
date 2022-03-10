<?php

namespace tractioms\controllers;

use Craft;
use craft\web\controller;
use yii\web\Response;
use craft\web\View;

use craft\db\Query;


class FormsController extends controller {

    protected $allowAnonymous = true;

    public function actionIndex():Response {

        $matrixBlocks = (new Query())
            ->select(['*'])
            ->from(['{{%fields}}'])
            ->where(['type' => 'craft\fields\Matrix'])
            ->all();

        $i=0;
        foreach($matrixBlocks as $block) {
            $matrixBlocks[$i]['fields'] = (new Query())
                ->select(['*'])
                ->from(['{{%matrixblocktypes}}'])
                ->where(['fieldId' => $block['id']])
                ->all();
            $i++;
        }

        $variables = [
            "data" => "this variable was set in the controller",
            "matrixBlocks" => $matrixBlocks
        ];

        // Craft::dd($results);

        return $this->renderTemplate(
            'test.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );


    }

}
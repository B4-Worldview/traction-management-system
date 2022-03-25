<?php

namespace b4worldview\tractionms\controllers;

use b4worldview\tractionms\TractionMS;
use Craft;
use craft\base\Plugin;
use craft\errors\MissingComponentException;
use craft\web\Controller;
use yii\web\BadRequestHttpException;

class DemoController extends Controller
{
    /**
     * @var Plugin
     */
    public Plugin $tractionMs;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->tractionMs = Craft::$app->plugins->getPlugin("tractionms");
    }

    /**
     * @throws MissingComponentException
     * @throws BadRequestHttpException
     */
    public function actionIndex()
    {
        $request = Craft::$app->getRequest();

        // getRequiredParam will throw an error if "text" is not supplied
        $textField = $request->getRequiredParam("textFieldDemo");

        // how to get the currently logged-in user
        $user = Craft::$app->getUser()->getIdentity();

        /*
        $mailer = Craft::$app->getMailer();

        $message = $mailer->compose()
            ->setTo($user->email)
            ->setSubject("Test Email from TractionMS Demo Tab")
            ->setHtmlBody("Text Field: " . $textField);

        $success = $message->send();
        */

        $success = TractionMS::getInstance()->sendEmail(
            $user->email,
            "Test Email from TractionMS Demo Tab",
            "Text Field: {$textField}"
        );

        if ($success) {
            Craft::$app->getSession()->setNotice("Success!");
        } else {
            Craft::$app->getSession()->setError("You have failed! That didn't work!");
        }
    }

}
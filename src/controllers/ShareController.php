<?php

namespace b4worldview\tractionms\controllers;

use b4worldview\tractionms\models\SettingsModel;
use b4worldview\tractionms\models\ShareEmailModel;
use b4worldview\tractionms\records\ReviewRecord;
use b4worldview\tractionms\TractionMS;
use Craft;
use craft\web\Controller;
use craft\web\View;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ShareController extends Controller
{

    /**
     * @inheritdoc
     *
     * This can also take an array of strings for the actions that are allowed
     * to be anonymous:
     *
     * public $allowAnoymous = ['index']
     *
     */
    public $allowAnonymous = true;


    public function actionIndex(): Response
    {
        
        $variables = [];
        $variables['errors'] = false;

        return $this->renderTemplate(
            'tractionms/share/email.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionReview(): Response
    {
        $success = false;
        $variables = [];
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        /** @var SettingsModel $settings */
        $settings = TractionMS::getInstance()->getSettings();

        $review = new ReviewRecord();
        $review->overall = $request->getBodyParam("overall");
        $review->favTrophy = $request->getBodyParam("favTrophy");
        $review->suggestions = $request->getBodyParam("suggestions");


        if ($review->validate()) {
            $review->save();

            $html = "
                <b>Overall Experience</b><br>
                {$review->overall}<br><br>
                
                <b>Favorite Trophy(s)</b><br>
                {$review->favTrophy}<br><br>
                
                <b>Suggestions for Improvement</b><br>
                {$review->suggestions}
            ";
            $success = TractionMS::getInstance()->sendEmail(
                $settings->leaveReviewEmail,
                "You have received a review of the Traction App",
                $html
            );

            $variables["success"] = $success;
            $variables['errors'] = false;
        } else {
            $variables["success"] = false;
            $variables["errors"] = true;
            $variables["review"] = $review;
        }

        return $this->asJson($variables);
    }


    /**
     * @throws BadRequestHttpException
     */
    public function actionByEmail(): Response
    {
        $success = false;
        $variables = [];
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        $submission = new ShareEmailModel();
        $submission->name = $request->getBodyParam("name");
        $submission->email = $request->getBodyParam("email");
        $submission->message = $request->getBodyParam("message");

        if ($submission->validate()) {
            $body = "
                {$submission->name} wanted to share the 
                <a href='https://traction.discipleshiptraining.org'>
                    Traction Adventure App 
                </a>
                (https://traction.discipleshiptraining.org) with you, and they shared the following
                message with you:<br><br>
                {$submission->message}
            ";
            $success = TractionMS::getInstance()->sendEmail(
                $submission->email,
                "Checkout the Traction Adventure App!",
                $body
            );
            $variables['errors'] = false;
        } else {
            $variables['submission'] = $submission;
            $variables['errors'] = true;
        }

        $variables["success"] = $success;

        if ($request->getAcceptsJson()) {
            return $this->asJson($variables);
        }

        return $this->renderTemplate(
            'tractionms/share/email.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );
    } // actionByEmail
} // class
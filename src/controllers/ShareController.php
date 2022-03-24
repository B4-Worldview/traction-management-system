<?php

namespace b4worldview\tractionms\controllers;

use Craft;
use craft\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use craft\web\View;
use b4worldview\tractionms\models\ShareEmailModel;
use b4worldview\tractionms\models\ShareReviewModel;

class ShareController extends Controller
{

    /**
     * @inheritdoc
     */
    public $allowAnonymous = true;


    public function actionIndex():Response
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

        $review = new ShareReviewModel();
        $review->overall = $request->getBodyParam("overall");
        $review->favTrophy = $request->getBodyParam("favTrophy");
        $review->suggestions = $request->getBodyParam("suggestions");


        if ($review->validate()) {
            $review->save();
            $success = $this->sendEmail(null, $review);
            $variables["success"] = $success;

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
            $success = $this->sendEmail($submission);
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

    }


    private function sendEmail(ShareEmailModel $submission = null, ShareReviewModel $review = null): bool
    {

        if ($submission != null) {
            $html = "
        {$submission->name} wanted to share the Traction Adventure App with you, and they shared the following
        message with you:<br><br>
        {$submission->message}
        ";

            return Craft::$app
                ->getMailer()
                ->compose()
                ->setTo($submission->email)
                ->setSubject("Checkout the Traction Adventure App!")
                ->setHtmlBody($html)
                ->send();
        }

        if ($review != null) {

            $html = "
            <b>Overall Experience</b><br>
            {$review->overall}<br><br>
            
            <b>Favorite Trophy(s)</b><br>
            {$review->favTrophy}<br><br>
            
            <b>Suggestions for Improvement</b><br>
            {$review->suggestions}
            ";

            return Craft::$app
                ->getMailer()
                ->compose()
                ->setTo("NathanBate81@gmail.com")
                ->setSubject("You have received a review of the Traction App")
                ->setHtmlBody($html)
                ->send();

        }
    }


}
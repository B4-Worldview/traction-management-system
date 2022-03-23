<?php

namespace b4worldview\tractionms\controllers;

use Craft;
use craft\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use craft\web\View;
use b4worldview\tractionms\models\ShareEmailModel;

class ShareController extends Controller
{

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
        return $this->renderTemplate(
            'tractionms/share/email.twig',
            $variables,
            View::TEMPLATE_MODE_SITE
        );

    }


    private function sendEmail(ShareEmailModel $submission): bool
    {
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
}
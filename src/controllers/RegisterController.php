<?php

namespace b4worldview\tractionms\controllers;

use b4worldview\tractionms\elements\ProfileElement;
use b4worldview\tractionms\elements\RegistrationElement;
use b4worldview\tractionms\models\RegistrationSubmissionModel;
use b4worldview\tractionms\models\SettingsModel;
use b4worldview\tractionms\TractionMS;
use Craft;
use craft\web\Controller;
use craft\web\Response;
use yii\web\BadRequestHttpException;

class RegisterController extends Controller
{
    /**
     * @var bool
     */
    public $allowAnonymous = true;


    /**
     * @return Response|null|void
     * @throws BadRequestHttpException
     */
    public function actionForDiscipleshipGroup()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        /** @var SettingsModel $settings */
        $settings = TractionMS::getInstance()->getSettings();

        $submission = new RegistrationSubmissionModel();
        $submission->attributes = $request->getBodyParams();

        if (!$submission->validate()) {
            static::setCraftFlashError($submission);
            return null;
        }

        $profile = new ProfileElement();
        $profile->firstName = $submission->firstName;
        $profile->lastName = $submission->lastName;
        $profile->age = $submission->age;
        $profile->professingChristian = $submission->professingChristian;
        $profile->timezone = $submission->timezone;
        $profileSuccess = Craft::$app->elements->saveElement($profile);

        if (!$profileSuccess) {
            static::setCraftFlashError($submission);
            return null;
        }

        $registration = new RegistrationElement();
        $registration->profileId = $profile->id;
        $registration->registrationType = 'DG';
        $registrationSuccess = Craft::$app->elements->saveElement($registration);

        if (!$registrationSuccess) {
            static::setCraftFlashError($submission);
            return null;
        }

        Craft::$app->getSession()->setNotice(
            "Thank you for your registration. Someone with contact you ASAP."
        );
        $this->redirectToPostedUrl($submission);
    }


    private static function setCraftFlashError($submission): void
    {
        Craft::$app->getSession()->setError(
            "The was a problem with the information you entered into the 
                registration form. Please check the form and try again."
        );
        Craft::$app->getUrlManager()->setRouteParams([
            'variables' => ['submission' => $submission]
        ]);
    }
}
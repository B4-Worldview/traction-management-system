<?php

namespace b4worldview\tractionms\controllers;

use b4worldview\tractionms\elements\RegistrationElement;
use b4worldview\tractionms\models\SettingsModel;
use b4worldview\tractionms\records\ProfileRecord;
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
     * @return Response|null
     * @throws BadRequestHttpException
     */
    public function actionForDiscipleshipGroup()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        /** @var SettingsModel $settings */
        $settings = TractionMS::getInstance()->getSettings();

        $profile = new ProfileRecord();
        $profile->firstName = $request->getRequiredParam('firstName');
        $profile->lastName = $request->getRequiredParam('lastName');
        $profile->age = $request->getRequiredParam('age');

        //Craft::dd($profile);

        if ($profile->validate()) {
            $profile->save();
            $profileId = $profile->id;

            $registration = new RegistrationElement();
            $registration->profileId = $profileId;
            $registration->registrationType = 'DG';

            $success = Craft::$app->elements->saveElement($registration);

            if (!$success) {
                Craft::dd($registration->getErrors());
            } else {
                $this->redirectToPostedUrl();
            }

        } else {
            Craft::dd($profile->getErrors());
        }

    }
}
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
    private static array $timeSlots = [
        "Monday_Morning" => "",
        "Monday_Afternoon" => "",
        "Monday_Evening" => "",
        "Tuesday_Morning" => "",
        "Tuesday_Afternoon" => "",
        "Tuesday_Evening" => "",
        "Wednesday_Morning" => "",
        "Wednesday_Afternoon" => "",
        "Wednesday_Evening" => "",
        "Thursday_Morning" => "",
        "Thursday_Afternoon" => "",
        "Thursday_Evening" => "",
        "Friday_Morning" => "",
        "Friday_Afternoon" => "",
        "Friday_Evening" => "",
        "Saturday_Morning" => "",
        "Saturday_Afternoon" => "",
        "Saturday_Evening" => "",
        "Sunday_Afternoon" => "",
    ];


    /**
     * @var bool
     */
    public $allowAnonymous = true;


    private $timeSlotsText = "";


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

        $preppedSlots = [];
        foreach (static::$timeSlots as $key => $slot) {
            $value = $request->getBodyParam($key);
            $preppedSlots[][$key] = $value != null ? 'Y' : 'N';

            if ($value != null) {
                $this->timeSlotsText .= str_replace("_", " ", $key) . "<br>";
            }
        }
        $submission->availableTimes = json_encode($preppedSlots);

        if (!$submission->validate()) {
            static::setCraftFlashError($submission);
            return null;
        }

        $profile = new ProfileElement();
        $profile->firstName = $submission->firstName;
        $profile->lastName = $submission->lastName;
        $profile->email = $submission->email;
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
        $registration->availableTimes = $submission->availableTimes;
        $registrationSuccess = Craft::$app->elements->saveElement($registration);

        if (!$registrationSuccess) {
            static::setCraftFlashError($submission);
            return null;
        }

        $environment = getenv('ENVIRONMENT');

        $body = "
            <b>Name:</b> {$submission->firstName} {$submission->lastName} <br>
            <b>Age:</b> {$submission->age} <br>
            <b>Faith Profession:</b> {$submission->professingChristian} <br><br>
            <b>Timeslots:</b> <br>
            {$this->timeSlotsText} <br>
            <b>Timezone:</b> {$submission->timezone} <br>
            <b>Email:</b> {$submission->email}
        ";
        TractionMS::getInstance()->sendEmail(
            $environment == 'dev' ? $settings->demoEmail : $settings->leaveReviewEmail,
            "REGISTRATION Discipleship Group",
            $body
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
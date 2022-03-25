<?php

namespace b4worldview\tractionms;

use b4worldview\tractionms\models\SettingsModel;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Event;
use yii\base\Exception;

/**
 * Custom module class.
 *
 * This class will be available throughout the system via:
 * `Craft::$app->getModule('my-module')`.
 *
 * You can change its module ID ("my-module") to something else from
 * config/app.php.
 *
 * If you want the module to get loaded on every request, uncomment this line
 * in config/app.php:
 *
 *     'bootstrap' => ['my-module']
 *
 * Learn more about Yii module development in Yii's documentation:
 * http://www.yiiframework.com/doc-2.0/guide-structure-modules.html
 */
class TractionMS extends Plugin
{

    public static $plugin;


    /**
     * @var bool
     */
    public $hasCpSection = true;


    /**
     * @var bool
     */
    public $hasCpSettings = true;


    /**
     * Initializes the module.
     */
    public function init()
    {
        parent::init();

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'modules\\console\\controllers';
        } else {
            $this->controllerNamespace = 'b4worldview\\tractionms\\controllers';
        }

        // Custom initialization code goes here...

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules["tractionms/user/register"] = ['template' => 'tractionms/user/user_registration.twig'];
                $event->rules["tractionms/user/register-success"] = ['template' => 'tractionms/user/user_registration_successful.twig'];
                $event->rules["tractionms/user/login"] = ['template' => 'tractionms/user/user_login.twig'];
                $event->rules["tractionms/user/profile"] = 'tractionms/user/profile';
                $event->rules["tractionms/user/profile/<status:\w+>"] = 'tractionms/user/profile';
                $event->rules["tractionms/user/change-email"] = ['template' => 'tractionms/user/user_change_email.twig'];
                $event->rules["tractionms/user/change-email-message"] = ['template' => 'tractionms/user/user_change_email_message.twig'];
                $event->rules["tractionms/share/"] = 'tractionms/share/index';
                $event->rules["tractionms/share/by-email"] = 'tractionms/share/by-email';
                $event->rules["tractionms/share/review"] = 'tractionms/share/review';
            }
        );

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $event) {
                $event->roots['tractionms'] = __DIR__ . '/templates';
            }
        );
    } // init

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @return bool
     */
    public function sendEmail($to, $subject, $body): bool
    {
        return Craft::$app
            ->getMailer()
            ->compose()
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }

    /**
     * @return SettingsModel
     */
    protected function createSettingsModel(): SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * @return string|null
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('tractionms/_cp/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}

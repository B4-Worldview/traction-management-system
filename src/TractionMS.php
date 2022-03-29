<?php

namespace b4worldview\tractionms;

use b4worldview\tractionms\elements\RegistrationElement;
use b4worldview\tractionms\models\SettingsModel;
use b4worldview\tractionms\services\RegistrationsService;
use b4worldview\tractionms\variables\RegistrationsVariable;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use craft\web\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Event;
use yii\base\Exception;


class TractionMS extends Plugin
{
    // Static
    /**
     * @var Plugin
     */
    public static Plugin $plugin;

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
        self::$plugin = $this;

        $this->_registerRoutes();
        $this->_registerElements();
        $this->_registerServices();
        $this->_registerVariables();
        $this->_registerTemplates();
        // $this->_registerCpNavItems();

    } // init

    /**
     * Registers the routes for the TractionMS plugin
     *
     * @return void
     */
    private function _registerRoutes(): void
    {
        /**
         * Figure out why we can use actions instead of a route.
         * In Twig: {{ actionUrl('plugin-name/controller/action')
         *
         * In Ben Croker's Tutorial on CraftQuest
         *https://craftquest.io/courses/in-depth-on-craft-plugin-development/8578
         * Time Stamp 2:25
         *
         */
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
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
    }


    /**
     * Registers the elements used with the TractionMS plugin
     *
     * @return void
     */
    private function _registerElements(): void
    {
        Event::on(Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            static function(RegisterComponentTypesEvent $event) {
                $event->types[] = RegistrationElement::class;
            }
        );
    }


    /**
     * Registers the services used with the TractionMS plugin
     *
     * @return void
     */
    private function _registerServices(): void
    {
        $this->setComponents([
            'registrations' => RegistrationsService::class
        ]);
    }


    /**
     * Registers the variables used in the TractionMS plugin
     *
     * @return void
     */
    private function _registerVariables(): void
    {
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            static function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('registrations', RegistrationsVariable::class);
            }
        );
    } // _registerElements function


    /**
     * Registers the templates for the TractionMS plugin
     *
     * @return void
     */
    private function _registerTemplates(): void
    {
        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['tractionms'] = __DIR__ . '/templates';
            }
        );
    }


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
    

    /**
     * @return void
     */
    private function _registerCpNavItems(): void
    {
        /*
         *
         * From: https://craftquest.io/courses/my-first-craft-cms-module/31216
         *
         Craft::setAlias('@cqcontrolpanel', __DIR__);
			parent::init();

			Event::on(
				Cp::class,
				Cp::EVENT_REGISTER_CP_NAV_ITEMS,
				function(RegisterCpNavItemsEvent $event) {
					$event->navItems[] = [
						'url' => 'entries/podcast',
						'label' => 'Podcast Episodes',
						'icon' => '@cqcontrolpanel/web/img/microphone.svg'
					];
				}
			);
         *
         */
    }
}

<?php
namespace b4worldview\tractionms;

use Craft;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;

use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;

use yii\base\Event;
use craft\base\Plugin;

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
                $event->rules["tractionms/register"] = 'tractionms/user/register';
            }
        );

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['tractionms_fe'] = __DIR__ . '/templates';
            }
        );

    }
}

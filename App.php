<?php namespace bot;

use Bot;
use yii\base\Module;
use yii\web\UrlManager;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Module is the base class for module and application classes.
 *
 * A module represents a sub-application which contains MVC
 * elements by itself, such as models, views, controllers, etc.
 *
 * To publish this Module in yii2 web app,
 * edit `@app/config/web.php` and add follow lines:
 *
 * ```
 * 'modules' => [
 *     'bot' => [
 *         'class' => 'bot\App',
 *     ]
 * ]
 * ```
 *
 * If you want to publish this Module in yii2 console app,
 * edit `@app/config/console.php` and add follow lines:
 *
 * ```
 * 'modules' => [
 *     'bot' => [
 *         'class' => 'bot\App',
 *         // 'baseUrl' => 'https://example.com'
 *     ]
 * ]
 * ```
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class App
 * @package bot
 */
class App extends Module implements BootstrapInterface
{

    /**
     * @var string of yii2 project web address
     */
    public $baseUrl = null;

    /**
     * @var string the default route of this module. Defaults
     * to `default`.
     *
     * The route may consist of child module ID, controller ID,
     * and/or action ID.
     *
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value
     * as specified in [[Controller::defaultAction]].
     */
    public $defaultRoute = 'bot';

    /**
     * @var string the namespace that controller classes are in.
     * This namespace will be used to load controller classes by
     * prepending it to the controller class name.
     *
     * If not set, it will use the `controllers` sub-namespace under
     * the namespace of this module. For example, if the namespace of this
     * module is `foo\bar`, then the default controller namespace would
     * be `foo\bar\controllers`.
     *
     * See also the [guide section on autoloading](guide:concept-autoloading)
     * to learn more about defining namespaces and how classes are loaded.
     */
    public $controllerNamespace = 'bot\web';

    /**
     * Initializes the module.
     *
     * This method is called after the module is created and initialized
     * with property values given in configuration. The default implementation
     * will initialize [[controllerNamespace]] if it is not set.
     *
     * If you override this method, please make sure you call the
     * parent implementation.
     */
    public function init()
    {
        require_once __DIR__ . '/base/Bot.php';
        parent::init();
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        // console app
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap[$this->id] = [
                'class' => 'bot\Console',
                'baseUrl' => $this->baseUrl
            ];
        }

        // web app
        else {
            $um = $app->urlManager;
            if ($um instanceof UrlManager) {
                $um->enablePrettyUrl = true;
                $um->addRules([
                    [
                        'route' => $this->id . '/bot/webhook',
                        'pattern' => 'bot/<id:\d+>/<key>'
                    ],
                    [
                        'route' => $this->id . '/bot/read-file',
                        'pattern' => 'bot/<username>'
                    ],
                    [
                        'route' => $this->id . '/bot/run-method',
                        'pattern' => 'bot/<username>/<method>'
                    ]
                ], true);
            }
        }
    }
}
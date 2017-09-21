<?php namespace bot\web;

use Bot;
use bot\helper\Bots;
use yii\base\Action;
use yii\web\Response;
use yii\web\Controller;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * BotController is the class of web controllers.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class BotController
 * @package bot\web
 */
class BotController extends Controller
{

    /**
     * @var Bots
     */
    private $bots;

    /**
     * @var bool whether to enable CSRF validation for the
     * actions in this controller.
     *
     * CSRF validation is enabled only when both this property
     * and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * This method is invoked right before an action is executed.
     *
     * The method will trigger the [[EVENT_BEFORE_ACTION]] event.
     * The return value of the method will determine whether the action
     * should continue to run.
     *
     * In case the action should not run, the request should be handled inside of the
     * `beforeAction` code by either providing the necessary output or redirecting
     * the request. Otherwise the response will be empty.
     *
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to run.
     */
    public function beforeAction($action)
    {
        $this->bots = new Bots('@app/bots');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Returns a list of behaviors that this component
     * should behave as.
     *
     * Child classes may override this method to specify the behaviors
     * they want to behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'webhook' => ['post']
                ]
            ]
        ];
    }

    /**
     * Whenever there is an update for the bot, telegram will send
     * an HTTPS POST request to the specified url,
     * containing a JSON-serialized Update.
     *
     * @param int $id the bot ID
     * @param string $key the bot private key
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionWebhook($id, $key)
    {
        $token = $id . ':' . $key;
        if ($this->bots->create($token) instanceof Bot) {
            $path = Bot::$path . '/src/bot.php';
            if (file_exists($path)) {
                try {
                    require $path;
                }
                catch (\Error $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
                catch (\ErrorException $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
                catch (\Exception $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
            }
            else {
                $message = 'Error: Not found ' . $path;
				
				Bot::error($message);
                throw new Exception($message);
            }
        }
        else {
            $message = 'Not found ' . $token;
            throw new NotFoundHttpException($message);
        }
    }

    /**
     * Sends a file to the browser.
     *
     * Note 
     * that this method only prepares the response for file
     * sending. The file is not sent until [[send()]] is called explicitly
     * or implicitly. The latter is done after you
     * return from a controller action.
     *
     * @param string $username the bot username
     * @param string $file the file path
     * @throws NotFoundHttpException
     */
    public function actionReadFile($username, $file)
    {
        if ($this->bots->create($username) instanceof Bot) {
            $basePath = realpath(Bot::$path . '/files');
            $path = realpath($basePath . '/' . $file);

            if (
				file_exists($path) && !is_dir($path) &&
				strpos($path, $basePath) === 0
			)
            {
				\Yii::$app->response->sendFile($path, basename($path), [
                    'inline' => true
                ]);
			}
            else {
                $message = 'Error: Not found ' . $file;
                throw new NotFoundHttpException($message);
            }
        }
        else {
            $message = 'Not found ' . $username;
            throw new NotFoundHttpException($message);
        }
    }

    /**
     * Require a bot method, by bot username and method
     * name. all bot methods exists in `@bots/username/methods`.
     *
     * @param string $username the bot username
     * @param string $method call php file by bot username and file name
     * @throws NotFoundHttpException
     */
    public function actionRunMethod($username, $method = 'index')
    {
        if ($this->bots->create($username) instanceof Bot) {
            $basePath = Bot::$path . '/methods';
            $path = $basePath . '/' . $method . '.php';

            if (file_exists($path)) {
                try {
                    require $path;
                }
                catch (\Error $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
                catch (\ErrorException $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
                catch (\Exception $e) {
                    $message = '[' . $e->getCode() . '] ' . $e->getMessage();
                    Bot::error($message);
                }
            }
            else {
                $message = 'Error: Not found ' . $method;
                throw new NotFoundHttpException($message);
            }
        }
        else {
            $message = 'Not found @' . $username;
            throw new NotFoundHttpException($message);
        }
    }
}
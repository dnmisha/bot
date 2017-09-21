<?php namespace bot;

use Bot;
use bot\helper\API;
use bot\helper\Bots;
use yii\base\Action;
use bot\object\User;
use yii\helpers\Url;
use bot\helper\Curl;
use bot\object\Error;
use bot\object\Update;
use yii\console\Controller;
use yii\base\InvalidParamException;

/**
 * System command line, allow us to control each bot we
 * have in project, like example get information, set webhook or
 * etc ...
 *
 * To publish this controller in Yii2 command line,
 * edit `@app/config/console.php` and add follow lines:
 *
 * ```
 * 'controllerMap' => [
 *     'bot' => [
 *         'class' => 'bot\Console',
 *         // 'baseUrl' => 'https://example.com'
 *     ]
 * ]
 * ```
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Console
 * @package bot
 */
class Console extends Controller
{

    /**
     * @var Bots
     */
    private $bots;

    /**
     * @var string of yii2 project web address
     */
    public $baseUrl = null;

    /**
     * This method is invoked right before an action is executed.
     *
     * The method will trigger the [[EVENT_BEFORE_ACTION]] event.
     * The return value of the method will determine whether the
     * action should continue to run.
     *
     * In case the action should not run, the request should be handled
     * inside of the `beforeAction` code by either providing the necessary
     * output or redirecting the request.
     * Otherwise the response will be empty.
     *
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to run.
     */
    public function beforeAction($action)
    {
        require_once __DIR__ . '/base/Bot.php';
        $this->bots = new Bots('@app/bots');

        return parent::beforeAction($action);
    }

    /**
     * Get help, how to work with these commands.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->run('/help', [ $this->id ]);
    }

    /**
     * Add new bot in project
     */
    public function actionAdd()
    {
        $token = $this->__getToken();
        $api = new API($token);

        $user = $api->getMe()->send();
        $username = $user->getUsername();

        $question = 'Is username of bot @' . $username . ' ?';
        $yes = $this->confirm($question, true);

        if ($yes) {
            $lowUsername = strtolower($username);
            $basePath = $this->bots->getPath();
            $message = '@' . $username . ' is already exits.';

            $path = $basePath . '/' . $lowUsername;
            if (!file_exists($path)) {
                $src = $path . '/src';
                mkdir($src, 0777, true);
                file_put_contents($path . '/.token', $token);
                file_put_contents($src . '/bot.php', "<?php namespace bot\\src;");
                $message = '@' . $username . ' added in ' . $src . '/bot.php';
            }

            $this->stdout($message);
        }

        $this->stdout("\n");
    }

    /**
     * Delete your bot from the project
     */
    public function actionDelete()
    {
        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            $path = Bot::$path;
            $message = 'Not found ' . $path;

            if (file_exists($path)) {
                $this->__deleteFolder($path);
                Bot::$api->deleteWebhook()->send();
                $message = 'You bot deleted successfully.';
            }
        }

        $this->stdout($message . "\n");
    }

    /**
     * Change the bot token to another one
     */
    public function actionChange()
    {
        $token = $this->__getToken();
        $api = new API($token);

        $user = $api->getMe()->send();
        $username = $user->getUsername();

        $question = 'Is the username of bot @' . $username . ' ?';
        $yes = $this->confirm($question, true);

        if ($yes) {
            $lowUsername = strtolower($username);
            $basePath = $this->bots->getPath();
            $message = 'There is no bot with this username.';

            $path = $basePath . '/' . $lowUsername;
            if (file_exists($path)) {
                file_put_contents($path . '/.token', $token);
                $message = 'The bot token changed to ' . $token;
            }

            $this->stdout($message);
        }

        $this->stdout("\n");
    }

    /**
     * Add new language to your bot
     */
    public function actionAddLang()
    {
        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            $path = Bot::$path . '/messages/';
            $language = $this->prompt('Enter Language code:', [
                'required' => true,
                'pattern' => '/^[a-z]{2}\_[A-Z]{2}$/',
                'error' => 'Invalid Language Code, [example: en_US].' . "\n"
            ]);

            $message = 'Language already exists.';
            if (!file_exists($path . $language)) {
                mkdir($path . $language, 0777, true);
                $path = $path . $language . '/bot.php';
                $content = '<?php' . "\n\n" . 'return [];';

                file_put_contents($path, $content);
                $message = 'Language created in ' . $path;
            }
        }

        $this->stdout($message . "\n");
    }

    /**
     * Delete language from your bot
     */
    public function actionDeleteLang()
    {
        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            $path = Bot::$path . '/messages/';
            $language = $this->prompt('Enter Language code:', [
                'required' => true,
                'pattern' => '/^[a-z]{2}\_[A-Z]{2}$/',
                'error' => 'Invalid Language Code, [example: en_US].' . "\n"
            ]);

            $message = 'Language already not exists.';
            if (file_exists($path . $language)) {
                $this->__deleteFolder($path . $language);
                $message = 'Language deleted successfully.';
            }
        }

        $this->stdout($message . "\n");
    }

    /**
     * Sets your bot webhook address
     */
    public function actionSetWebhook()
    {
        $um = \Yii::$app->urlManager;
        $um->enablePrettyUrl = true;

        if ($this->baseUrl !== null) {
            $um->setScriptUrl($this->baseUrl);
        }
        else {
            $this->prompt('Enter yii project base address:', [
                'required' => true,
                'validator' => function ($input) use ($um) {
                    $input = preg_replace('#^http[s]?:\/\/#', 'https://', $input);
                    $input = strpos($input, 'https://') === 0 ? $input : 'https://' . $input;
                    $um->setScriptUrl($input);
                    return true;
                }
            ]);
        }

        $um->addRules([
            [
                'route' => $this->id . '/bot/webhook',
                'pattern' => 'bot/<id:\d+>/<key>'
            ]
        ], true);

        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            $url = Url::toRoute([
                $this->id . '/bot/webhook',
                'id' => Bot::$id,
                'key' => Bot::$key
            ], 'https');

            $mc = intval($this->prompt('Enter max connections:', [
                'default' => 40
            ]));

            $mc = $mc < 0 ? 0 : ($mc > 100 ? 100 : $mc);

            $res = Bot::$api->setWebhook()
                ->setMaxConnections($mc)
                ->setUrl($url)
                ->send();

            if ($res instanceof Error) {
                $code = '[' . $res->getErrorCode() . ']';
                $message = $code . ' ' . $res->getDescription() . '.';
            }
            else {
                $message = 'succeed to set webhook.';
            }

            $message .= "\n" . 'Url: ' . $url;
        }

        $this->stdout($message . "\n");
    }

    /**
     * Delete your bot webhook
     */
    public function actionDeleteWebhook()
    {
        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            Bot::$api->deleteWebhook()->send();
            $message = 'Webhook deleted successfully.';
        }

        $this->stdout($message . "\n");
    }

    /**
     * Get your bot info
     */
    public function actionGetMe()
    {
        $bot = $this->__chooseBot();
        $message = 'Not found any bot.';

        if ($bot instanceof Bot) {
            $getMe = \Bot::$api->getMe()->send();

            if ($getMe instanceof Error) {
                $code = '[' . $getMe->getErrorCode() . ']';
                $message = $code . ' ' . $getMe->getDescription() . '.';
            }
            else {
                $message = "\n";
                $message .= 'ID: ' . $getMe->getId() . "\n";
                $message .= 'Name: ' . $getMe->getFirstName() . "\n";
                $message .= 'Username: @' . $getMe->getUsername() . "\n";
                $message .= 'Token: ' . \Bot::$token . "\n";
                $message .= 'Path: ' . \Yii::getAlias('@bot/src/bot.php') . "\n";

                $info = \Bot::$api->getWebhookInfo()->send();
                if ($info->hasSetWebhook()) {
                    $message .= 'Webhook: ' . $info->getUrl();
                }
                else {
                    $message .= 'Webhook: Not found any webhook.';
                }
            }
        }

        $this->stdout($message . "\n");
    }

    /**
     * Return bot unique authentication token
     * @return string
     */
    private function __getToken()
    {
        return $this->prompt('Enter the token:', [
            'required' => true,
            'pattern' => '/(\d+)\:(.*)/',
            'error' => 'Invalid Bot Token.' . "\n",
            'validator' => function ($input) {
                try {
                    $api = new API($input);
                    $res = $api->getMe()->send();
                    if ($res instanceof User) {
                        return true;
                    }
                }
                catch (InvalidParamException $e) {
                }

                return false;
            }
        ]);
    }

    /**
     * Return Bot object
     * @return bool|Bot|false|string|static
     */
    private function __chooseBot()
    {
        $bots = $this->bots->_allBots();
        $count = sizeof($bots);
        $usernames = array_keys($bots);

        // there is no bot
        if ($count == 0) {
            return false;
        }

        // there is only bot
        if ($count == 1) {
            $username = $usernames[0];

            $message = '@' . $username . ' is only your choose, continue ?';
            $ok = $this->confirm($message, true);
            return $ok ? $this->bots->create($username) : false;
        }

        $select = $this->select('choose one bot', array_flip($usernames));
        return $this->bots->create($select);
    }

    /**
     * @param string $dir_name
     * @return bool
     */
    private function __deleteFolder($dir_name)
    {
        $dir_handle = false;
        if (is_dir($dir_name)) $dir_handle = opendir($dir_name);
        if (!$dir_handle) return false;

        while($file = readdir($dir_handle)) {
            if ($file != '.' && $file != '..') {
                if (!is_dir($dir_name . '/' . $file)) {
                    unlink($dir_name . '/' . $file);
                }
                else {
                    $this->__deleteFolder($dir_name . '/' . $file);
                }
            }
        }

        closedir($dir_handle);
        rmdir($dir_name);
        return true;
    }
}
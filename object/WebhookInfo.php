<?php namespace bot\object;

/**
 * Contains information about the current status of a webhook.
 *
 * @method bool hasLastErrorDate()
 * @method bool hasLastErrorMessage()
 * @method bool hasMaxConnections()
 * @method bool hasAllowedUpdates()
 * @method string getUrl()
 * @method bool getHasCustomCertificate()
 * @method int getPendingUpdateCount()
 * @method int getLastErrorDate($default = null)
 * @method string getLastErrorMessage($default = null)
 * @method int getMaxConnections($default = null)
 * @method array getAllowedUpdates($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class WebhookInfo
 * @package bot\object
 * @link https://core.telegram.org/bots/api#webhookinfo
 */
class WebhookInfo extends Object
{

    /**
     * Check out the webhook url isset or not.
     * @return bool
     */
    public function hasSetWebhook()
    {
        return $this->hasUrl() && !empty($this->getUrl());
    }

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [];
    }
}
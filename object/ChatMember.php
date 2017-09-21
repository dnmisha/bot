<?php namespace bot\object;

/**
 * This object contains information about one member of the chat.
 *
 * @method bool hasUntilDate()
 * @method bool hasCanBeEdited()
 * @method bool hasCanChangeInfo()
 * @method bool hasCanPostMessages()
 * @method bool hasCanEditMessages()
 * @method bool hasCanDeleteMessages()
 * @method bool hasCanInviteUsers()
 * @method bool hasCanRestrictMembers()
 * @method bool hasCanPinMessages()
 * @method bool hasCanPromoteMembers()
 * @method bool hasCanSendMessages()
 * @method bool hasCanSendMediaMessages()
 * @method bool hasCanSendOtherMessages()
 * @method bool hasCanAddWebPagePreviews()
 * @method User getUser()
 * @method string getStatus()
 * @method int getUntilDate($default = null)
 * @method bool getCanBeEdited($default = null)
 * @method bool getCanChangeInfo($default = null)
 * @method bool getCanPostMessages($default = null)
 * @method bool getCanEditMessages($default = null)
 * @method bool getCanDeleteMessages($default = null)
 * @method bool getCanInviteUsers($default = null)
 * @method bool getCanRestrictMembers($default = null)
 * @method bool getCanPinMessages($default = null)
 * @method bool getCanPromoteMembers($default = null)
 * @method bool getCanSendMessages($default = null)
 * @method bool getCanSendMediaMessages($default = null)
 * @method bool getCanSendOtherMessages($default = null)
 * @method bool getCanAddWebPagePreviews($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class ChatMember
 * @package bot\object
 * @link https://core.telegram.org/bots/api#chatmember
 */
class ChatMember extends Object
{

    /**
     * @return bool
     */
    public function isCreator()
    {
        return $this->getStatus() == 'creator';
    }

    /**
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->getStatus() == 'administrator';
    }

    /**
     * @return bool
     */
    public function isMember()
    {
        return $this->getStatus() == 'member';
    }

    /**
     * @return bool
     */
    public function isRestricted()
    {
        return $this->getStatus() == 'restricted';
    }

    /**
     * @return bool
     */
    public function isLeft()
    {
        return $this->getStatus() == 'left';
    }

    /**
     * @return bool
     */
    public function isKicked()
    {
        return $this->getStatus() == 'kicked';
    }

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [
            'user' => User::className()
        ];
    }
}
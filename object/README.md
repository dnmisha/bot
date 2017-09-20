## Available types
All types used in the Bot API responses are represented as PHP-objects.
> Optional fields may be not returned when irrelevant.


##### Class bot\object\User
This object represents a Telegram user or bot.

Method|Field|Type|Description
------|-----|----|-----------
getId()|id|Integer|Unique identifier for this user or bot
isId()|is_bot|Boolean|True, if this user is a bot
getFirstName()|first_name|String|User‘s or bot’s first name
hasLastName(), getLastName()|last_name|String|Optional. User‘s or bot’s last name
hasUsername(), getUsername()|username|String|Optional. User‘s or bot’s username
hasLanguageCode(), getLanguageCode()|language_code|String|Optional. IETF language tag of the user's language

##### Class bot\object\Chat
This object represents a chat.

Method|Field|Type|Description
------|-----|----|-----------
getId()|id|Integer|Unique identifier for this chat. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
getType()|type|String|Type of chat, can be either “private”, “group”, “supergroup” or “channel”
hasTitle(), getTitle()|title|String|Optional. Title, for supergroups, channels and group chats
hasUsername(), getUsername()|username|String|Optional. Username, for private chats, supergroups and channels if available
hasFirstName(), getFirstName()|first_name|String|Optional. First name of the other party in a private chat
hasLastName(), getLastName()|last_name|String|Optional. Last name of the other party in a private chat
hasAllMembersAreAdministrators(), getAllMembersAreAdministrators()|all_members_are_administrators|Boolean|Optional. True if a group has ‘All Members Are Admins’ enabled.
hasPhoto(), getPhoto()|photo|ChatPhoto|Optional. Chat photo. Returned only in getChat.
hasDescription(), getDescription()|description|String|Optional. Description, for supergroups and channel chats. Returned only in getChat.
hasInviteLink(), getInviteLink()|invite_link|String|Optional. Chat invite link, for supergroups and channel chats. Returned only in getChat.
hasPinnedMessage(), getPinnedMessage()|pinned_message|Message|Optional. Pinned message, for supergroups. Returned only in getChat.


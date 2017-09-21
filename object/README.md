## Available types
All types used in the Bot API responses are represented as PHP-objects.
> Optional fields may be not returned when irrelevant.

---

### User
This object represents a Telegram user or bot.

Method|Description
------|-----------
getId()|Unique identifier for this user or bot
isBot()|True, if this user is a bot
getFirstName()|User‘s or bot’s first name
hasLastName(), getLastName()|Optional. User‘s or bot’s last name
hasUsername(), getUsername()|Optional. User‘s or bot’s username
hasLanguageCode(), getLanguageCode()||Optional. IETF language tag of the user's language

### Chat
This object represents a chat.

Method|Description
------|-----------
getId()|Unique identifier for this chat. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
getType()|Type of chat, can be either “private”, “group”, “supergroup” or “channel”
hasTitle(), getTitle()|Optional. Title, for supergroups, channels and group chats
hasUsername(), getUsername()|Optional. Username, for private chats, supergroups and channels if available
hasFirstName(), getFirstName()|Optional. First name of the other party in a private chat
hasLastName(), getLastName()|Optional. Last name of the other party in a private chat
hasAllMembersAreAdministrators(), getAllMembersAreAdministrators()|Optional. True if a group has ‘All Members Are Admins’ enabled.
hasPhoto(), getPhoto()|Optional. Chat photo. Returned only in getChat.
hasDescription(), getDescription()|Optional. Description, for supergroups and channel chats. Returned only in getChat.
hasInviteLink(), getInviteLink()|Optional. Chat invite link, for supergroups and channel chats. Returned only in getChat.
hasPinnedMessage(), getPinnedMessage()|Optional. Pinned message, for supergroups. Returned only in getChat.

###

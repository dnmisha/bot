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

### Message
This object represents a message.

Method|Description
------|-----------
getMessageId()|Unique message identifier inside this chat
getFrom(), hasFrom()|Optional. Sender, empty for messages sent to channels
getDate()|Date the message was sent in Unix time
getChat()|Conversation the message belongs to
getForwardFrom()|Optional. For forwarded messages, sender of the original message
getForwardFromChat()|Optional. For messages forwarded from channels, information about the original channel
getForwardFromMessageId()|Optional. For messages forwarded from channels, identifier of the original message in the channel
getForwardSignature()|Optional. For messages forwarded from channels, signature of the post author if present
getForwardDate()|Optional. For forwarded messages, date the original message was sent in Unix time
getReplyToMessage()|Optional. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
getEditDate()|Optional. Date the message was last edited in Unix time
getAuthorSignature()|Optional. Signature of the post author for messages in channels
getText()|Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters.
getEntities()|Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
getAudio()|Optional. Message is an audio file, information about the file
getDocument()|Optional. Message is a general file, information about the file
getGame()|Optional. Message is a game, information about the game. [More about games »](https://core.telegram.org/bots/api#games)
getPhoto()|Optional. Message is a photo, available sizes of the photo
getSticker()|Optional. Message is a sticker, information about the sticker
getVideo()|Optional. Message is a video, information about the video
getVoice()|Optional. Message is a voice message, information about the file
getVideoNote()|Optional. Message is a [video note](https://telegram.org/blog/video-messages-and-telescope), information about the video message
getCaption()|Optional. Caption for the document, photo or video, 0-200 characters
getContact()|Optional. Message is a shared contact, information about the contact
getLocation()|Optional. Message is a shared location, information about the location
getVenue()|Optional. Message is a venue, information about the venue
getNewChatMembers()|Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
getNewChatMember()|Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
getLeftChatMember()|Optional. A member was removed from the group, information about them (this member may be the bot itself)
getNewChatTitle()|Optional. A chat title was changed to this value
getNewChatPhoto()|Optional. A chat photo was change to this value
getDeleteChatPhoto()|Optional. Service message: the chat photo was deleted
getGroupChatCreated()|Optional. Service message: the group has been created
getSupergroupChatCreated()|Optional. Service message: the supergroup has been created. This field can‘t be received in a message coming through updates, because bot can’t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
getChannelChatCreated()|Optional. Service message: the channel has been created. This field can‘t be received in a message coming through updates, because bot can’t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
getMigrateToChatId()|Optional. The group has been migrated to a supergroup with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
getMigrateFromChatId()|Optional. The supergroup has been migrated from a group with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
getPinnedMessage()|Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
getInvoice()|Optional. Message is an invoice for a [payment](https://core.telegram.org/bots/api#payments), information about the invoice. [More about payments »](https://core.telegram.org/bots/api#payments)
getSuccessfulPayment()|Optional. Message is a service message about a successful payment, information about the payment. [More about payments »](https://core.telegram.org/bots/api#payments)

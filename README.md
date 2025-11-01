# WAHA PHP

**Unofficial** PHP client library for [WAHA (WhatsApp HTTP API)](https://waha.devlike.pro) - a powerful solution to interact with WhatsApp Web through HTTP API.

[![Unofficial](https://img.shields.io/badge/Status-Unofficial-red.svg)](https://github.com/teguh02/waha-php)
[![PHP Version](https://img.shields.io/badge/php-8.0+-blue.svg)](https://www.php.net/downloads)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![WAHA](https://img.shields.io/badge/WAHA-2025.9-orange.svg)](https://waha.devlike.pro)
[![Packagist](https://img.shields.io/packagist/v/teguh02/waha-php.svg)](https://packagist.org/packages/teguh02/waha-php)
[![Downloads](https://img.shields.io/packagist/dt/teguh02/waha-php.svg)](https://packagist.org/packages/teguh02/waha-php)

## Features

✅ **Complete API Coverage** - Support for all WAHA endpoints
- 📤 **Send Messages**: Text, Image, Video, Voice, File, Location, Contact, Poll
- 📥 **Receive Messages**: Webhooks and Event Handling
- 💬 **Chats Management**: List, Archive, Read, Delete
- 👤 **Contacts Management**: Get, Update, Block, Check Existence
- 👥 **Groups Management**: Create, Manage, Admin Controls
- 🟢 **Status Management**: Send and Manage WhatsApp Status
- 📢 **Channels Management**: Create and Manage Channels
- 🔐 **Session Management**: Create, Start, Stop, QR Code
- And much more!

✅ **Simple & Intuitive** - Clean, PHPic API design
✅ **Type Hints** - Full type hints for better IDE support
✅ **Error Handling** - Comprehensive error handling
✅ **Documentation** - Complete documentation and examples

## Installation

### Using Composer (Recommended)

```bash
composer require teguh02/waha-php
```

### Or install from source:

```bash
git clone https://github.com/teguh02/waha-php.git
cd waha-php
composer install
```

## Quick Start

### 1. Start WAHA Server

First, you need to have WAHA server running. Follow the [Quick Start Guide](https://waha.devlike.pro/docs/overview/quick-start/):

```bash
docker pull devlikeapro/waha
docker run -it --env-file .env -v "$(pwd)/sessions:/app/.sessions" --rm -p 3000:3000 --name waha devlikeapro/waha
```

### 2. Use the PHP Client

```php
<?php

use WahaPhp\Client;

require_once 'vendor/autoload.php';

// Initialize the client
$client = new Client(
    'http://localhost:3000',
    'your-api-key-here'  // Optional, if you set WAHA_API_KEY
);

// Send a text message
$result = $client->messages()->sendText(
    'default',
    '1234567890@c.us',
    'Hello from PHP! 👋'
);

var_dump($result);
```

### 3. Create Session with QR Code

```php
// Create a new session
$session = $client->sessions()->create(
    name: 'my_session',
    config: [
        'webhooks' => [[
            'url' => 'https://your-webhook-url.com/webhook',
            'events' => ['message']
        ]]
    ]
);

// Get QR code for authentication
$qrCode = $client->sessions()->getQr('my_session', acceptJson: true);
echo "QR Code (Base64): " . $qrCode['data'] . "\n";

// Scan the QR code with your WhatsApp app
// The session status will change to WORKING
```

### 4. Receive Messages with Webhooks

Create a webhook server:

```php
<?php

use WahaPhp\Client;

$client = new Client('http://localhost:3000', 'your-api-key');

// Handle webhook (example with Slim framework)
$app->post('/webhook', function ($request, $response) use ($client) {
    $data = $request->getParsedBody();
    
    if ($data['event'] === 'message') {
        $payload = $data['payload'];
        $fromNumber = $payload['from'];
        $messageText = $payload['body'] ?? '';
        
        echo "Received: $messageText from $fromNumber\n";
        
        // Reply to the message
        $client->messages()->sendText(
            $data['session'],
            $fromNumber,
            "You said: $messageText"
        );
    }
    
    return $response->withJson(['status' => 'ok']);
});
```

## Complete Examples

### Send Different Types of Messages

```php
use WahaPhp\Client;

$client = new Client('http://localhost:3000', 'your-api-key');

// Send text message
$client->messages()->sendText(
    'default',
    '1234567890@c.us',
    'Hello World!'
);

// Send image with URL
$client->messages()->sendImage(
    'default',
    '1234567890@c.us',
    ['url' => 'https://example.com/image.jpg', 'mimetype' => 'image/jpeg'],
    'Check this out!'
);

// Send image from file
$client->messages()->sendImage(
    'default',
    '1234567890@c.us',
    'path/to/image.jpg',
    'My image'
);

// Send video
$client->messages()->sendVideo(
    'default',
    '1234567890@c.us',
    ['url' => 'https://example.com/video.mp4', 'mimetype' => 'video/mp4']
);

// Send voice message
$client->messages()->sendVoice(
    'default',
    '1234567890@c.us',
    ['url' => 'https://example.com/voice.opus', 'mimetype' => 'audio/ogg; codecs=opus']
);

// Send document
$client->messages()->sendFile(
    'default',
    '1234567890@c.us',
    ['url' => 'https://example.com/document.pdf', 'mimetype' => 'application/pdf']
);

// Send location
$client->messages()->sendLocation(
    'default',
    '1234567890@c.us',
    38.8937255,
    -77.0969763,
    'My Location'
);

// Send contact
$client->messages()->sendContact(
    'default',
    '1234567890@c.us',
    [[
        'fullName' => 'John Doe',
        'organization' => 'Company',
        'phoneNumber' => '+91 11111 11111',
        'whatsappId' => '911111111111'
    ]]
);

// Send poll
$client->messages()->sendPoll(
    'default',
    '1234567890@c.us',
    [
        'name' => 'How are you?',
        'options' => ['Awesome!', 'Good!', 'Not bad!'],
        'multipleAnswers' => false
    ]
);
```

### Manage Sessions

```php
// List all active sessions
$sessions = $client->sessions()->list();

// List all sessions including stopped ones
$allSessions = $client->sessions()->list(allSessions: true);

// Get specific session
$session = $client->sessions()->getSession('default');

// Create session
$newSession = $client->sessions()->create(
    name: 'my_session',
    config: ['webhooks' => []]
);

// Start session
$client->sessions()->start('my_session');

// Stop session
$client->sessions()->stop('my_session');

// Restart session
$client->sessions()->restart('my_session');

// Logout session
$client->sessions()->logout('my_session');

// Delete session
$client->sessions()->delete('my_session');

// Get QR code
$qr = $client->sessions()->getQr('default', acceptJson: true);

// Request pairing code
$codeInfo = $client->sessions()->requestCode('default', '12132132130');
echo "Pairing code: " . $codeInfo['code'] . "\n";
```

### Manage Chats

```php
// List all chats
$chats = $client->chats()->list('default');

// Get chat picture
$picture = $client->chats()->getPicture('default', '1234567890@c.us');

// Archive chat
$client->chats()->archive('default', '1234567890@c.us');

// Unarchive chat
$client->chats()->unarchive('default', '1234567890@c.us');

// Mark as unread
$client->chats()->unread('default', '1234567890@c.us');

// Read messages
$client->chats()->readMessages('default', '1234567890@c.us');

// Get messages
$messages = $client->chats()->getMessages('default', '1234567890@c.us', limit: 100);

// Get specific message
$message = $client->chats()->getMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);

// Delete chat
$client->chats()->delete('default', '1234567890@c.us');
```

### Manage Contacts

```php
// List all contacts
$contacts = $client->contacts()->listAll('default');

// Get specific contact
$contact = $client->contacts()->getContact('default', '1234567890');

// Update contact
$client->contacts()->update(
    'default',
    '1234567890@c.us',
    'John',
    'Doe'
);

// Check if phone exists
$result = $client->contacts()->checkExists('default', '1234567890');
if ($result['numberExists']) {
    echo "Chat ID: " . $result['chatId'] . "\n";
}

// Get contact about
$about = $client->contacts()->getAbout('default', '1234567890');

// Get profile picture
$profilePic = $client->contacts()->getProfilePicture('default', '1234567890');

// Block contact
$client->contacts()->block('default', '1234567890@c.us');

// Unblock contact
$client->contacts()->unblock('default', '1234567890@c.us');
```

### Manage Groups

```php
// List all groups
$groups = $client->groups()->list('default');

// Get specific group
$group = $client->groups()->get('default', '1234567890@g.us');

// Create group
$newGroup = $client->groups()->create(
    'default',
    'My New Group',
    ['1234567890@c.us']
);

// Update group name
$client->groups()->updateSubject('default', '1234567890@g.us', 'Updated Name');

// Update group description
$client->groups()->updateDescription('default', '1234567890@g.us', 'Description');

// Get invite code
$inviteCode = $client->groups()->getInviteCode('default', '1234567890@g.us');

// Revoke invite code
$client->groups()->revokeInviteCode('default', '1234567890@g.us');

// Get participants
$participants = $client->groups()->getParticipants('default', '1234567890@g.us');

// Add participants
$client->groups()->addParticipants(
    'default',
    '1234567890@g.us',
    ['9876543210@c.us']
);

// Remove participants
$client->groups()->removeParticipants(
    'default',
    '1234567890@g.us',
    ['9876543210@c.us']
);

// Promote to admin
$client->groups()->promoteAdmin(
    'default',
    '1234567890@g.us',
    ['9876543210@c.us']
);

// Demote from admin
$client->groups()->demoteAdmin(
    'default',
    '1234567890@g.us',
    ['9876543210@c.us']
);

// Leave group
$client->groups()->leave('default', '1234567890@g.us');
```

### Manage Status (Stories)

```php
// Send text status
$client->status()->sendText('default', 'My status update');

// Send image status
$client->status()->sendImage(
    'default',
    ['url' => 'https://example.com/image.jpg', 'mimetype' => 'image/jpeg']
);

// Send video status
$client->status()->sendVideo(
    'default',
    ['url' => 'https://example.com/video.mp4', 'mimetype' => 'video/mp4']
);

// Send voice status
$client->status()->sendVoice(
    'default',
    ['url' => 'https://example.com/voice.opus', 'mimetype' => 'audio/ogg; codecs=opus']
);

// Delete status
$client->status()->delete('default', 'message_id_here');

// Get new message ID
$messageId = $client->status()->getNewMessageId('default');
```

### Manage Channels

```php
// List all channels
$channels = $client->channels()->list('default');

// Get specific channel
$channel = $client->channels()->get('default', 'channel_id');

// Create channel
$newChannel = $client->channels()->create('default', 'My Channel', 'Description');

// Get channel messages
$messages = $client->channels()->getMessages('default', 'channel_id', limit: 100);

// Delete channel
$client->channels()->delete('default', 'channel_id');
```

### Message Reactions and Actions

```php
// Add reaction
$client->messages()->addReaction(
    'default',
    'message_id_here',
    '👍'
);

// Remove reaction
$client->messages()->addReaction(
    'default',
    'message_id_here',
    ''
);

// Star message
$client->messages()->starMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);

// Unstar message
$client->messages()->starMessage(
    'default',
    '1234567890@c.us',
    'message_id_here',
    star: false
);

// Edit message
$client->messages()->editMessage(
    'default',
    '1234567890@c.us',
    'message_id_here',
    'Updated message'
);

// Delete message
$client->messages()->deleteMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);

// Forward message
$client->messages()->forwardMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);

// Pin message
$client->messages()->pinMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);

// Unpin message
$client->messages()->unpinMessage(
    'default',
    '1234567890@c.us',
    'message_id_here'
);
```

## Error Handling

```php
use WahaPhp\Client;
use WahaPhp\Exception\WahaAuthenticationException;
use WahaPhp\Exception\WahaNotFoundException;

try {
    $client = new Client('http://localhost:3000', 'wrong-key');
    $result = $client->messages()->sendText(
        'default',
        '1234567890@c.us',
        'Hello'
    );
} catch (WahaAuthenticationException $e) {
    echo "Authentication failed\n";
} catch (WahaNotFoundException $e) {
    echo "Resource not found\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Requirements

- PHP 8.0+
- Guzzle HTTP library
- Composer
- WAHA server running (see [Quick Start Guide](https://waha.devlike.pro/docs/overview/quick-start/))

## Documentation

- [WAHA Documentation](https://waha.devlike.pro)
- [WAHA GitHub](https://github.com/devlikeapro/waha)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

MIT License - see LICENSE file for details

## Support

- Packagist: [https://packagist.org/packages/teguh02/waha-php](https://packagist.org/packages/teguh02/waha-php)
- GitHub Issues: [https://github.com/teguh02/waha-php/issues](https://github.com/teguh02/waha-php/issues)
- WAHA Documentation: [https://waha.devlike.pro](https://waha.devlike.pro)

## Important Disclaimer

**This is an UNOFFICIAL community project** and is not affiliated, associated, authorized, endorsed by, or in any way officially connected with:
- WhatsApp LLC or any of its subsidiaries or affiliates
- WAHA (devlikeapro) team

The official WhatsApp website can be found at [whatsapp.com](https://whatsapp.com).  
The official WAHA documentation can be found at [waha.devlike.pro](https://waha.devlike.pro).

"WhatsApp" as well as related names, marks, emblems and images are registered trademarks of their respective owners.

### Usage Warning

This library interacts with WhatsApp through unofficial means. There are risks associated with using unofficial WhatsApp clients:
- Account suspension or banning
- Security risks
- Data privacy concerns
- No official support

Use at your own risk. For business applications, we recommend using the [official WhatsApp Business API](https://developers.facebook.com/docs/whatsapp).


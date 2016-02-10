microsoft-translator
================================

[![Build Status](https://travis-ci.org/badams/microsoft-translator.svg?branch=master)](https://travis-ci.org/badams/microsoft-translator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/badams/microsoft-translator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/badams/microsoft-translator/?branch=master)

PHP implementation of [Microsoft's Translator API](https://msdn.microsoft.com/en-us/library/ff512419.aspx)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Install using the following command.

```bash
composer require badams/microsoft-translator
```

Alternatively you can add the following to your `composer.json` file.

```javascript
    "require" : {
        "badams/microsoft-translator": "*"
    }
```

## Usage

*Note:* This library is still in the early stage of development and interfaces are likely to change in the near future.

Translate a string of text from one language to another

```php

use badams\MicrosoftTranslator\MicrosoftTranslator;

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->addClient($clientId, $clientSecret);

$output = $translator->translate('Hello World!', $to = 'fr', $from = 'en');

echo $output; // Salut tout le monde!

```


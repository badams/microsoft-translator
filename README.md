microsoft-translator
================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/badams/microsoft-translator/master.svg?style=flat-square)](https://travis-ci.org/badams/microsoft-translator)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/badams/microsoft-translator.svg?style=flat-square)](https://scrutinizer-ci.com/g/badams/microsoft-translator/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/badams/microsoft-translator.svg?style=flat-square)](https://scrutinizer-ci.com/g/badams/microsoft-translator)

PHP implementation of [Microsoft's Translator API](https://msdn.microsoft.com/en-us/library/ff512419.aspx)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Install using the following command.

```bash
$ composer require badams/microsoft-translator
```

Alternatively you can add the following to your `composer.json` file.

```javascript
    "require" : {
        "badams/microsoft-translator": "*"
    }
```

## Usage

```php

use badams\MicrosoftTranslator\MicrosoftTranslator;

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->setClient($clientId, $clientSecret);

```

Translate a string of text from one language to another

```php
$output = $translator->translate('Hello World!', $to = 'fr', $from = 'en');
echo $output; // Salut tout le monde!

```

Detect the language of a string

```php
$language = $translator->detect('Salut tout le monde!');
echo $language; // fr
echo $language->getEnglishName(); // French

``

Returns a wave or mp3 stream of the passed-in text being spoken in the desired language.

```php
$data = $translator->speak('Salut tout le monde!', 'fr');

header('Content-Type: audio/mp3');
echo base64_decode($data);

``


## Testing

`MicrosoftTranslator` has a [PHPUnit](https://phpunit.de) test suite. To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.


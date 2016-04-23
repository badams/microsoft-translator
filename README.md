[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/badams/microsoft-translator/master.svg?style=flat-square)](https://travis-ci.org/badams/microsoft-translator)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/badams/microsoft-translator.svg?style=flat-square)](https://scrutinizer-ci.com/g/badams/microsoft-translator/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/badams/microsoft-translator.svg?style=flat-square)](https://scrutinizer-ci.com/g/badams/microsoft-translator)

MicrosoftTranslator - An easy to use PHP implementation of [Microsoft's Translator API](https://msdn.microsoft.com/en-us/library/ff512419.aspx)
================================

The goal of this project is to provide a modern, elegant, and feature complete implementation of the Microsoft Translation HTTP API to PHP developers.

## Currently supported methods

 - Translate: Converts a text string from one language to another.
 - Detect: Detects the language of a selection of text.
 - Speak: Returns a string with a URL to a wave stream of the passed in text in desired language.
 - GetLanguagesForSpeak: Obtains a list of the language codes supported by the Translator Service for speech synthesis.
 - GetLanguageNames: Retrieves localized names for the languages passed to it.
 - GetLanguagesForTranslate: Obtains a list of the language codes supported by the Translator Service.
 - GetTranslations: Returns an array of alternative translations of the given text.
 - GetTranslationsArray: Returns an array of alternative translations of the passed array of text.

## Roadmap

API methods that are yet to be implemented

 - TranslateArray
 - DetectArray
 - TransformText
 - AddTranslation
 - AddTranslationArray

## Installation

Install `badams/microsoft-translator` using Composer.

```bash
$ composer require badams/microsoft-translator
```

## Basic Usage

```php

use badams\MicrosoftTranslator\MicrosoftTranslator;

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->setClient($clientId, $clientSecret);

// Translate a string of text from one language to another
$output = $translator->translate('Hello World!', $to = 'fr', $from = 'en');
echo $output; // Salut tout le monde!

// Detect the language of a string
$language = $translator->detect('Salut tout le monde!');
echo $language; // fr
echo $language->getEnglishName(); // French

//Returns a wave or mp3 stream of the passed-in text being spoken in the desired language.
$data = $translator->speak('Salut tout le monde!', 'fr');

header('Content-Type: audio/mp3');
echo base64_decode($data);

```

## Testing

`MicrosoftTranslator` has a [PHPUnit](https://phpunit.de) test suite. To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.


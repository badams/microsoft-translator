
Detect
==========

Detects the language of a selection of text.

## Arguments

```php
detect($text)
```

| Argument     | Type    | Description |
| -------------| ------- | ----------- |
| $text        | string  | **Required.** A string containing some text whose language is to be identified, cannot exceed 10,000 characters or an `ArgumentException` will be thrown. |


## Return Values

An instance of `\badams\MicrosoftTranslator\Language` will be returned, for more information see the documentation for the [Language](language.md) class.

## Errors/Exceptions

- If the contents of `$text` exceeds 10000 characters then an `ArgumentException` will be thrown.

For any other errors or exceptions, please check the documentation for [general errors and exceptions](errors.md)

## Examples

```php
use badams\MicrosoftTranslator\MicrosoftTranslator;
use badams\MicrosoftTranslator\Language;


$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->setClient($clientId, $clientSecret);

// Detect the language of a string of text.

$lang = $translator->detect('Hello World!');
echo sprintf('Language appears to be: %s (%s)' $lang->getEnglishName(), $lang); // "Language appears to be: English (en)"
```
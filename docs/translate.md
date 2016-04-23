
Translate
==========

Converts a string of text from one language to another.

## Arguments

```php 
translate($text, $to, $from = null, $contentType = Translate::CONTENT_TYPE_PLAIN)
```

| Argument     | Type    | Description |
| -------------| ------- | ----------- |
| $text        | string  | **Required.** The text you wish to translate, cannot exceed 10,000 characters or an `ArgumentException` will be thrown. |
| $to          | mixed   | **Required.** The language which you wish to translate the given text to. Must be either a string in the form of a language code (eg. `"en"` or `"de"`) or an instance of `\badams\MicrosoftTranslator\Language`. |
| $from        | mixed   | **Optional.** The originating language of the text. Must be either a string in the form of a language code (eg. `"en"` or `"de"`) or an instance of `\badams\MicrosoftTranslator\Language`. If omitted the API will attempt to detect the originating language. |
| $contentType | string  | **Optional.** The format of the text being translated. The supported formats are `Translate::CONTENT_TYPE_PLAIN` and `Translate::CONTENT_TYPE_HTML`. Any HTML needs to be well-formed. 


## Return Values

A string representing the translated text.

## Errors/Exceptions

- If the contents of `$text` exceeds 10000 characters then an `ArgumentException` will be thrown.
- If the arguments `$to` or `$from` are not valid language codes then an `UnsupportedLanguageException` will be thrown.
- If `$contentType` does not match either `text/plain` or `text/html` then an `ArgumentException` will be thrown.

For any other errors or exceptions, please check the documentation [general errors and exceptions](errors.md)

## Examples

```php
use badams\MicrosoftTranslator\MicrosoftTranslator;
use badams\MicrosoftTranslator\Language;
use badams\MicrosoftTranslator\Methods\Translate;

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->setClient($clientId, $clientSecret);

// Translate from English to French

$output = $translator->translate('Hello World!', 'fr');
echo $output;

// Using Language Objects,

$to = new Language('de');
$from = new Language('fr');

echo sprintf('Translating from %s, to %s', $from->getEnglishName(), $to->getEnglishName());
echo $translator->translate('Salut tout le monde!', $to, $from);

// Translate HTML

$html = '<p>This is some text I <em>would like to</em> translate</p>';
echo $translator->translate($html, 'fr', 'en', Translate::CONTENT_TYPE_HTML);

```
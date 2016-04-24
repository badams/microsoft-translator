
Speak
==========

Generates a wave form of synthesised speech from the given text/language combination.

## Arguments

```php
speak($text, $language, $format = Speak::FORMAT_MP3, $options = Speak::OPTION_MAX_QUALITY)
```

| Argument     | Type    | Description |
| -------------| ------- | ----------- |
| $text        | string  | **Required.** The text you wish to convert to synthesised speech|
| $language    | mixed   | **Required.** The desired language for the text to be spoken in. Must be either a string in the form of a language code (eg. `"en"`) or an instance of `\badams\MicrosoftTranslator\Language`. |
| $format      | string  | **Optional.** The audio format to be returned, must be either `audio/wav` or `audio/mp3`. |
| $options | string  | **Optional.** A string specifying the quality of the audio signals. Currently, `MaxQuality` and `MinSize` are available. With `MaxQuality`, you can get the voice(s) with the highest quality, and with `MinSize`, you can get the voices with the smallest size. If no value is provided, we default to `MinSize`.


## Return Values

A string of base64 encoded audio data.

## Errors/Exceptions

- If the contents of `$text` exceeds 2000 characters then an `ArgumentException` will be thrown.
- If the argument `$language` are not valid language codes then an `UnsupportedLanguageException` will be thrown.
- If `$format` does not match either `audio/mp3` or `audio/wav` then an `ArgumentException` will be thrown.
- If `$options` does not match either `MaxQuality` or `MinSize` then an `ArgumentException` will be thrown.

For any other errors or exceptions, please check the documentation [general errors and exceptions](errors.md)

## Examples

```php
use badams\MicrosoftTranslator\MicrosoftTranslator;
use badams\MicrosoftTranslator\Language;
use badams\MicrosoftTranslator\Methods\Speak;

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

$translator = new MicrosoftTranslator();
$translator->setClient($clientId, $clientSecret);

// Speak french
$data = $translator->speak('Salut tout le monde!', 'fr');
header('Content-Type: audio/mp3');
echo base64_decode($data);

// Use wav format
$data = $translator->speak('This is a wave file', 'en', Speak::FORMAT_WAV);
header('Content-Type: audio/wav');
echo base64_decode($data);

// Min Size Option
$data = $translator->speak('This is a wave file', 'en', Speak::FORMAT_MP3, Speak::OPTION_MIN_SIZE);
header('Content-Type: audio/mp3');
echo base64_decode($data);
```
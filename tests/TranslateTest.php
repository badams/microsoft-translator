<?php

namespace badams\MicrosoftTranslator\tests;

use badams\MicrosoftTranslator\Methods\Translate;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class TranslateTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfulTranslate()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory('<string>Hallo</string>')),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $this->assertEquals('Hallo', $translator->translate('Hello', 'de', 'en'));
    }

    public function testSuccessfulTranslateHtml()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory('<string>&lt;p&gt;Bonjour &lt;em&gt;monde!&lt;/em&gt;&lt;/p&gt;</string>')),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');
        $result = $translator->translate('<p>Hello <em>world!</em></p>', 'fr', 'en', Translate::CONTENT_TYPE_HTML);
        $this->assertEquals('<p>Bonjour <em>monde!</em></p>', $result);
    }

    public function testInvalidLanguage()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException',
            'foo is not a supported language code'
        );

        new Translate('Foo Bar', 'foo');
    }

    public function testMaximumText()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            sprintf('The length of the text must not exceed %s characters.', Translate::TEXT_MAX_LENGTH)
        );

        $text = 'Hello';

        for ($i = 0; $i < Translate::TEXT_MAX_LENGTH; $i++) {
            $text .= ' World';
        }

        new Translate($text, 'en');
    }

    public function testInvalidContentType()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            'text/csv is not a valid content type.'
        );

        new Translate('Hello World', 'fr', 'en', 'text/csv');
    }
}

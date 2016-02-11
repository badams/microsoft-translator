<?php

namespace badams\MicrosoftTranslator\Tests;

use badams\MicrosoftTranslator\Methods\Speak;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class SpeakTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfuSpeak()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory('AUDIO_DATA')),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');
        $output = base64_decode($translator->speak('Hello', 'en'));
        $this->assertEquals('AUDIO_DATA', $output);
    }

    public function testInvalidLanguage()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException',
            'foobar is not a supported language code'
        );

        new Speak('Foo Bar', 'foobar');
    }
}
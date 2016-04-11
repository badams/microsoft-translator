<?php

namespace badams\MicrosoftTranslator\tests;

use badams\MicrosoftTranslator\Methods\Detect;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class DetectTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfulDetect()
    {
        $client = new Client();
        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory('<string>en</string>')),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $result = $translator->detect('Hello');

        $this->assertInstanceOf('\badams\MicrosoftTranslator\Language', $result);
        $this->assertEquals('en', (string)$result);
        $this->assertEquals('English', $result->getEnglishName());
    }

    public function testInvalidTextLength()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            sprintf('The length of the text must not exceed %s characters.', Detect::TEXT_MAX_LENGTH)
        );

        $text = 'Hello';

        for ($i = 0; $i < Detect::TEXT_MAX_LENGTH; $i++) {
            $text .= ' World';
        }

        new Detect($text);
    }
}

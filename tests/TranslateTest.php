<?php

namespace badams\MicrosoftTranslator\Tests;

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

}
<?php

namespace badams\MicrosoftTranslator\tests;

use badams\MicrosoftTranslator\Methods\Speak;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class GetLanguagesForSpeakTest extends \PHPUnit_Framework_TestCase
{
    protected $xmlRepsonse = <<<XML
    <ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
        <string>en</string>
        <string>de</string>
        <string>fr</string>
    </ArrayOfstring>
XML;

    public function testGetLanguagesForSpeak()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory($this->xmlRepsonse)),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $languages = $translator->getLanguagesForSpeak();
        $this->assertTrue(is_array($languages));
        $this->assertEquals('en', $languages[0]);
        $this->assertEquals('de', $languages[1]);
        $this->assertEquals('fr', $languages[2]);
    }
}

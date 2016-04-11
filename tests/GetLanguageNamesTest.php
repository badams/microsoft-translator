<?php

namespace badams\MicrosoftTranslator\tests;

use badams\MicrosoftTranslator\Methods\GetLanguageNames;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class GetLanguageNamesTest extends \PHPUnit_Framework_TestCase
{
    protected $xmlRepsonse = <<<XML
    <ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
        <string>English</string>
        <string>German</string>
        <string>French</string>
    </ArrayOfstring>
XML;

    public function testGetLanguageNames()
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

        $languages = $translator->getLanguageNames(['en', 'de', 'fr']);
        $this->assertTrue(is_array($languages));
        $this->assertEquals(3, count($languages));
        $this->assertEquals('English', $languages[0]);
        $this->assertEquals('German', $languages[1]);
        $this->assertEquals('French', $languages[2]);
    }

    public function testInvalidLocale()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException',
            'invalid is not a supported language code'
        );

        new GetLanguageNames('INVALID', []);
    }

    public function testInvalidLanguageCode()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');
        $xml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><string></string></ArrayOfstring>';

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory($xml)),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $languages = $translator->getLanguageNames(['invalid_language']);
        $this->assertTrue(is_array($languages));
        $this->assertTrue(empty($languages));
    }
}

<?php

namespace badams\MicrosoftTranslator\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use badams\MicrosoftTranslator\MicrosoftTranslator;
use GuzzleHttp\Stream\Stream;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    protected $quotaExceededResponse = "<html><body><h1>TranslateApiException</h1><p>Method: Translate()</p><p>Message: The Azure Market Place Translator Subscription associated with the request credentials has zero balance.</p></body></html>";

    protected $argumentExceptionResponse = "<html><body><h1>Argument Exception</h1><p>Error Message Here</p></body></html>";

    protected $expiredTokenResponse = "<html><body><h1>Argument Exception</h1><p>Method: Translate()</p><p>Parameter: </p><p>Message: The incoming token has expired. Get a new access token from the Authorization Server.</p></body></html>";


    public function testConstructor()
    {
        $translator = new MicrosoftTranslator();
        $reflection = new \ReflectionClass($translator);
        $client = $reflection->getProperty('http');
        $client->setAccessible(true);
        $this->assertNotNull($client->getValue($translator));
        $this->assertInstanceOf('GuzzleHttp\Client', $client->getValue($translator));
    }

    public function testAccountQuotaExceeded()
    {
        $client = new Client();

        $mock = new Mock([
            new Response(200, [], Stream::factory('{"access_token":"valid"}')),
            new Response(400, [], Stream::factory($this->quotaExceededResponse))
        ]);

        $client->getEmitter()->attach($mock);

        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\QuotaExceededException',
            strip_tags($this->quotaExceededResponse)
        );

        $translator = new MicrosoftTranslator($client);
        $translator->translate('Hello', 'en', 'de');
    }

    public function testArgumentException()
    {
        $client = new Client();

        $mock = new Mock([
            new Response(200, [], Stream::factory('{"access_token":"valid"}')),
            new Response(400, [], Stream::factory($this->argumentExceptionResponse))
        ]);

        $client->getEmitter()->attach($mock);

        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            strip_tags($this->argumentExceptionResponse)
        );

        $translator = new MicrosoftTranslator($client);
        $translator->translate('Hello', 'en', 'de');
    }

    public function testExpiredToken()
    {
        $client = new Client();

        $mock = new Mock([
            new Response(200, [], Stream::factory('{"access_token":"valid"}')),
            new Response(400, [], Stream::factory($this->expiredTokenResponse)),
            new Response(200, [], Stream::factory('{"access_token":"valid"}')),
            new Response(200, [], Stream::factory("<string>Salut</string>")),
        ]);

        $client->getEmitter()->attach($mock);
        $translator = new MicrosoftTranslator($client);
        $this->assertEquals('Salut', $translator->translate('Hello', 'en', 'fr'));
    }

    public function testUnhandledException()
    {
        $client = new Client();

        $mock = new Mock([
            new Response(200, [], Stream::factory('{"access_token":"valid"}')),
            new Response(500, [], Stream::factory('Unknown Error'))
        ]);

        $client->getEmitter()->attach($mock);

        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\TranslatorException',
            'Unknown Error'
        );

        $translator = new MicrosoftTranslator($client);
        $translator->translate('Hello', 'en', 'de');
    }
}





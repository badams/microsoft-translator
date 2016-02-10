<?php

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use badams\MicrosoftTranslator\MicrosoftTranslator;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testSetClient()
    {
        $client = new Client();
        $content = GuzzleHttp\Stream\Stream::factory('{"access_token":"valid"}');
        $mock = new Mock([new Response(200, [], $content)]);
        $client->getEmitter()->attach($mock);

        $translator = new MicrosoftTranslator($client);

        $reflection = new ReflectionClass($translator);

        $translator->setClient('client_id', 'client_secret');

        $clientId = $reflection->getProperty('clientId');
        $clientId->setAccessible(true);
        $this->assertEquals('client_id', $clientId->getValue($translator));

        $clientSecret = $reflection->getProperty('clientSecret');
        $clientSecret->setAccessible(true);
        $this->assertEquals('client_secret', $clientSecret->getValue($translator));

    }

    public function testInvalidClient()
    {
        $client = new Client();

        $content = GuzzleHttp\Stream\Stream::factory('{"error" : "invalid_client", "error_description" : "ACS50012: Authentication failed."}');

        $mock = new Mock([
            new Response(400, [], $content),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\AuthException',
            'ACS50012: Authentication failed.'
        );

        $reflection = new ReflectionClass($translator);
        $accessToken = $reflection->getMethod('getAccessToken');
        $accessToken->setAccessible(true);
        $accessToken->invoke($translator);
    }


}
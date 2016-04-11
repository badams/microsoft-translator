<?php

namespace badams\MicrosoftTranslator\tests;

use badams\MicrosoftTranslator\Methods\GetTranslations;
use badams\MicrosoftTranslator\Methods\GetTranslationsArray;
use badams\MicrosoftTranslator\TranslateOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class GetTranslationsArrayTest extends \PHPUnit_Framework_TestCase
{
    private $xmlResponse = <<<XML
    <ArrayOfGetTranslationsResponse xmlns="http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
        <GetTranslationsResponse>
            <From>en</From>
            <Translations>
                <TranslationMatch>
                    <Count>0</Count>
                    <MatchDegree>100</MatchDegree>
                    <MatchedOriginalText/>
                    <Rating>5</Rating>
                    <TranslatedText>Hallo</TranslatedText>
                </TranslationMatch>
                <TranslationMatch>
                    <Count>1</Count>
                    <MatchDegree>70</MatchDegree>
                    <MatchedOriginalText>Hello</MatchedOriginalText>
                    <Rating>4</Rating>
                    <TranslatedText>Hello</TranslatedText>
                </TranslationMatch>
            </Translations>
        </GetTranslationsResponse>
        <GetTranslationsResponse>
            <From>en</From>
            <Translations>
                <TranslationMatch>
                    <Count>0</Count>
                    <MatchDegree>100</MatchDegree>
                    <MatchedOriginalText/>
                    <Rating>5</Rating>
                    <TranslatedText>Hallo</TranslatedText>
                </TranslationMatch>
                <TranslationMatch>
                    <Count>1</Count>
                    <MatchDegree>100</MatchDegree>
                    <MatchedOriginalText/>
                    <Rating>5</Rating>
                    <TranslatedText>Hello</TranslatedText>
                </TranslationMatch>
            </Translations>
        </GetTranslationsResponse>
    </ArrayOfGetTranslationsResponse>
XML;

    public function testSuccessfulTranslate()
    {
        $client = new Client();

        $content = Stream::factory('{"access_token":"123"}');

        $mock = new Mock([
            new Response(200, [], $content),
            new Response(200, [], Stream::factory($this->xmlResponse)),
        ]);

        $client->getEmitter()->attach($mock);

        $translator = new \badams\MicrosoftTranslator\MicrosoftTranslator($client);
        $translator->setClient('client_id', 'client_secret');

        $results = $translator->getTranslationsArray(['Hello', 'World'], 'de', 'en');

        $this->assertTrue(is_array($results));
        $this->assertEquals(2, count($results));

        $this->assertInstanceOf('\badams\MicrosoftTranslator\Responses\GetTranslationsResponse', $results[0]);
        $this->assertInstanceOf('\badams\MicrosoftTranslator\Language', $results[0]->getFrom());
        $this->assertEquals('en', (string)$results[0]->getFrom());

        $translations = $results[0]->getTranslations();
        $this->assertEquals(2, count($translations));
        $this->assertInstanceOf('\badams\MicrosoftTranslator\Responses\TranslationMatch', $translations[0]);
        $this->assertEquals('Hallo', $translations[0]->getTranslatedText());
        $this->assertEquals(5, $translations[0]->getRating());
        $this->assertEquals(100, $translations[0]->getMatchDegree());
        $this->assertEquals(null, $translations[0]->getError());
        $this->assertEquals(0, $translations[0]->getCount());
        $this->assertEquals('', $translations[0]->getMatchedOriginalText());

        $this->assertEquals('Hello', $translations[1]->getTranslatedText());
        $this->assertEquals(4, $translations[1]->getRating());
        $this->assertEquals(70, $translations[1]->getMatchDegree());
        $this->assertEquals(null, $translations[1]->getError());
        $this->assertEquals(1, $translations[1]->getCount());
        $this->assertEquals('Hello', $translations[1]->getMatchedOriginalText());
    }

    public function testXmlBodyCreation()
    {
        $options = new TranslateOptions('general', 'text/plain', 'foobar', 'http://uri.com', '1234', 'A');
        $method = new GetTranslationsArray(['a', 'b', 'c'], 'de', 'en', 100, $options);

        $reflection = new \ReflectionClass($method);

        $createBodyXml = $reflection->getMethod('createBodyXml');
        $createBodyXml->setAccessible(true);
        $body = $createBodyXml->invoke($method);

        $xml = simplexml_load_string($body);

        $this->assertEquals('en', (string)$xml->From);
        $this->assertEquals('de', (string)$xml->To);
        $this->assertEquals('100', (string)$xml->MaxTranslations);
        $this->assertEquals('a', (string)$xml->Texts->string[0]);
        $this->assertEquals('b', (string)$xml->Texts->string[1]);
        $this->assertEquals('c', (string)$xml->Texts->string[2]);

        $this->assertEquals('general', (string)$xml->Options->Category);
        $this->assertEquals('text/plain', (string)$xml->Options->ContentType);
        $this->assertEquals('foobar', (string)$xml->Options->State);
        $this->assertEquals('http://uri.com', (string)$xml->Options->Uri);
        $this->assertEquals('1234', (string)$xml->Options->User);
        $this->assertEquals('A', (string)$xml->Options->ReservedFlag);
    }

    public function testInvalidTextsArg()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            'texts must be an array'
        );

        new GetTranslationsArray('Testing', 'en', 'de');
    }

    public function testMaxTextsCount()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            'Amount of texts cannot exceed '.GetTranslationsArray::MAX_TEXTS
        );

        $texts = array_pad([], 11, 'Hello World!');

        new GetTranslationsArray($texts, 'en', 'de');
    }

    public function testMaxTextsLength()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            'Total length of texts cannot exceed '.GetTranslationsArray::TEXT_MAX_LENGTH
        );

        $text = str_pad('', 2000, 'Hello world! ');
        $texts = array_pad([], 6, $text);

        new GetTranslationsArray($texts, 'en', 'de');
    }
}

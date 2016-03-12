<?php

namespace badams\MicrosoftTranslator\Tests;

use badams\MicrosoftTranslator\Methods\GetTranslations;
use badams\MicrosoftTranslator\TranslateOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class GetTranslationsTest extends \PHPUnit_Framework_TestCase
{
    private $xmlResponse = <<<XML
    <GetTranslationsResponse xmlns="http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
        <From>en</From>
        <State>Foo</State>
        <Translations>
            <TranslationMatch>
                <Count>0</Count>
                <MatchDegree>100</MatchDegree>
                <MatchedOriginalText/>
                <Rating>5</Rating>
                <TranslatedText>Hello</TranslatedText>
            </TranslationMatch>
        </Translations>
    </GetTranslationsResponse>
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

        $results = $translator->getTranslations('Hello', 'de', 'en');

        $this->assertInstanceOf('\badams\MicrosoftTranslator\Responses\GetTranslationsResponse', $results);
        $this->assertInstanceOf('\badams\MicrosoftTranslator\Language', $results->getFrom());
        $this->assertEquals('en', (string)$results->getFrom());

        $translation = $results->getTranslations()[0];

        $this->assertInstanceOf('\badams\MicrosoftTranslator\Responses\TranslationMatch', $translation);
        $this->assertEquals('Hello', $translation->getTranslatedText());
        $this->assertEquals(5, $translation->getRating());
        $this->assertEquals(100, $translation->getMatchDegree());
        $this->assertEquals(null, $translation->getError());
        $this->assertEquals(0, $translation->getCount());
    }

    public function testCustomOptions()
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

        $options = new TranslateOptions('general', TranslateOptions::CONTENT_TYPE_PLAIN, 'Foo');
        $results = $translator->getTranslations('Hello', 'de', 'en', 5, $options);

        $this->assertEquals('Foo', $results->getState());

    }

    public function testInvalidToLanguage()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException',
            'foo is not a supported language code'
        );

        new GetTranslations('Foo Bar', 'foo', 'bar');
    }

    public function testMaximumText()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            sprintf('The length of the text must not exceed %s characters.', GetTranslations::TEXT_MAX_LENGTH)
        );

        $text = 'Hello';

        for ($i = 0; $i < GetTranslations::TEXT_MAX_LENGTH; $i++) {
            $text .= ' World';
        }

        new GetTranslations($text, 'de', 'en');
    }
}
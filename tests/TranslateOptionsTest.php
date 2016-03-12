<?php

namespace badams\MicrosoftTranslator\Tests;

use badams\MicrosoftTranslator\TranslateOptions;


class TranslateOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $options = new TranslateOptions(
            'general',
            TranslateOptions::CONTENT_TYPE_PLAIN,
            'foo',
            'uri',
            'user',
            'flag'
        );

        $reflection = new \ReflectionClass($options);

        $category = $reflection->getProperty('category');
        $category->setAccessible(true);
        $this->assertEquals('general', $category->getValue($options));

        $contentType = $reflection->getProperty('contentType');
        $contentType->setAccessible(true);
        $this->assertEquals(TranslateOptions::CONTENT_TYPE_PLAIN, $contentType->getValue($options));

        $state = $reflection->getProperty('state');
        $state->setAccessible(true);
        $this->assertEquals('foo', $state->getValue($options));

        $uri = $reflection->getProperty('uri');
        $uri->setAccessible(true);
        $this->assertEquals('uri', $uri->getValue($options));

        $user = $reflection->getProperty('user');
        $user->setAccessible(true);
        $this->assertEquals('user', $user->getValue($options));

        $reservedFlag = $reflection->getProperty('reservedFlag');
        $reservedFlag->setAccessible(true);
        $this->assertEquals('flag', $reservedFlag->getValue($options));
    }

    public function testInvalidContentType()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\ArgumentException',
            'Foo is not a valid content type.'
        );

        new TranslateOptions('general', 'Foo');
    }

    public function testXml()
    {
        $options = new TranslateOptions(
            'general',
            TranslateOptions::CONTENT_TYPE_PLAIN,
            'foo',
            'uri',
            'user',
            'flag'
        );

        $xml = simplexml_load_string($options->xml());

        $this->assertEquals('general', (string)$xml->Category);
        $this->assertEquals(TranslateOptions::CONTENT_TYPE_PLAIN, (string)$xml->ContentType);
        $this->assertEquals('flag', (string)$xml->ReservedFlag);
        $this->assertEquals('foo', (string)$xml->State);
        $this->assertEquals('uri', (string)$xml->Uri);
        $this->assertEquals('user', (string)$xml->User);
    }
}
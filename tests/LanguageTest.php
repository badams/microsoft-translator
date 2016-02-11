<?php

namespace badams\MicrosoftTranslator\Tests;

use badams\MicrosoftTranslator\Language;


class LanguageTest extends \PHPUnit_Framework_TestCase
{
    public function testLanguageObject()
    {
        $en = new Language('fr');
        $this->assertEquals('fr', (string) $en);
        $this->assertEquals('French', $en->getEnglishName());
    }

    public function testInvalidLanguageCode()
    {
        $this->setExpectedException(
            '\badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException',
            'foo is not a supported language code'
        );

        new Language('foo');

    }

}
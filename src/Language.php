<?php
namespace badams\MicrosoftTranslator;

use badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException;

/**
 * Class Language
 * @package badams\MicrosoftTranslator
 */
class Language
{
    /**
     * @var string
     */
    protected $code;

    /**
     * List of officially supported language code
     *
     * @var array
     */
    private $languages = [
        'ar' => 'Arabic',
        'bs-Latn' => 'Bosnian (Latin)',
        'bg' => 'Bulgarian',
        'ca' => 'Catalan',
        'zh-CHS' => 'Chinese Simplified',
        'zh-CHT' => 'Chinese Traditional',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'nl' => 'Dutch',
        'en' => 'English',
        'et' => 'Estonian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'de' => 'German',
        'el' => 'Greek',
        'ht' => 'Haitian Creole',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'mww' => 'Hmong Daw',
        'hu' => 'Hungarian',
        'id' => 'Indonesian',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'sw' => 'Kiswahili',
        'tlh' => 'Klingon',
        'tlh-Qaak' => 'Klingon (pIqaD)',
        'ko' => 'Korean',
        'lv' => 'Latvian',
        'lt' => 'Lithuanian',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'no' => 'Norwegian',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'otq' => 'Querétaro Otomi',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sr-Cyrl' => 'Serbian (Cyrillic)',
        'sr-Latn' => 'Serbian (Latin)',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'es' => 'Spanish',
        'sv' => 'Swedish',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'vi' => 'Vietnamese',
        'cy' => 'Welsh',
        'yua' => 'Yucatec Maya',
    ];

    /**
     * Language constructor.
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = strtolower((string)$code);

        if (!array_key_exists($this->code, $this->languages)) {
            throw new UnsupportedLanguageException(sprintf('%s is not a supported language code', $this->code));
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return string;
     */
    public function getEnglishName()
    {
        return $this->languages[$this->code];
    }
}
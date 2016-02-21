<?php
/**
 * This file is part of the badams\MicrosoftTranslator library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/badams/microsoft-translator
 * @package badams/microsoft-translator
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace badams\MicrosoftTranslator;

use badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException;

/**
 * Class Language
 * @package badams\MicrosoftTranslator
 */
class Language
{
    const ARABIC = 'ar';
    const BOSNIAN_LATIN = 'bs-latn';
    const BULGARIAN = 'bg';
    const CATALAN = 'ca';
    const CHINESE_SIMPLIFIED = 'zh-chs';
    const CHINESE_TRADITIONAL = 'zh-cht';
    const CROATIAN = 'hr';
    const CZECH = 'cs';
    const DANISH = 'da';
    const DUTCH = 'nl';
    const ENGLISH = 'en';
    const ESTONIAN = 'et';
    const FINNISH = 'fi';
    const FRENCH = 'fr';
    const GERMAN = 'de';
    const GREEK = 'el';
    const HAITIAN_CREOLE = 'ht';
    const HEBREW = 'he';
    const HINDI = 'hi';
    const HMONG_DAW = 'mww';
    const HUNGARIAN = 'hu';
    const INDONESIAN = 'id';
    const ITALIAN = 'it';
    const JAPANESE = 'ja';
    const KISWAHILI = 'sw';
    const KLINGON = 'tlh';
    const KLINGON_PIQAD = 'tlh-qaak';
    const KOREAN = 'ko';
    const LATVIAN = 'lv';
    const LITHUANIAN = 'lt';
    const MALAY = 'ms';
    const MALTESE = 'mt';
    const NORWEGIAN = 'no';
    const PERSIAN = 'fa';
    const POLISH = 'pl';
    const PORTUGUESE = 'pt';
    const QUERETARO_OTOMI = 'otq';
    const ROMANIAN = 'ro';
    const RUSSIAN = 'ru';
    const SERBIAN_CYRILLIC = 'sr-cyrl';
    const SERBIAN_LATIN = 'sr-latn';
    const SLOVAK = 'sk';
    const SLOVENIAN = 'sl';
    const SPANISH = 'es';
    const SWEDISH = 'sv';
    const THAI = 'th';
    const TURKISH = 'tr';
    const UKRAINIAN = 'uk';
    const URDU = 'ur';
    const VIETNAMESE = 'vi';
    const WELSH = 'cy';
    const YUCATEC_MAYA = 'yua';

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
        self::ARABIC => 'Arabic',
        self::BOSNIAN_LATIN => 'Bosnian (Latin)',
        self::BULGARIAN => 'Bulgarian',
        self::CATALAN => 'Catalan',
        self::CHINESE_SIMPLIFIED => 'Chinese Simplified',
        self::CHINESE_TRADITIONAL => 'Chinese Traditional',
        self::CROATIAN => 'Croatian',
        self::CZECH => 'Czech',
        self::DANISH => 'Danish',
        self::DUTCH => 'Dutch',
        self::ENGLISH => 'English',
        self::ESTONIAN => 'Estonian',
        self::FINNISH => 'Finnish',
        self::FRENCH => 'French',
        self::GERMAN => 'German',
        self::GREEK => 'Greek',
        self::HAITIAN_CREOLE => 'Haitian Creole',
        self::HEBREW => 'Hebrew',
        self::HINDI => 'Hindi',
        self::HMONG_DAW => 'Hmong Daw',
        self::HUNGARIAN => 'Hungarian',
        self::INDONESIAN => 'Indonesian',
        self::ITALIAN => 'Italian',
        self::JAPANESE => 'Japanese',
        self::KISWAHILI => 'Kiswahili',
        self::KLINGON => 'Klingon',
        self::KLINGON_PIQAD => 'Klingon (pIqaD)',
        self::KOREAN => 'Korean',
        self::LATVIAN => 'Latvian',
        self::LITHUANIAN => 'Lithuanian',
        self::MALAY => 'Malay',
        self::MALTESE => 'Maltese',
        self::NORWEGIAN => 'Norwegian',
        self::PERSIAN => 'Persian',
        self::POLISH => 'Polish',
        self::PORTUGUESE => 'Portuguese',
        self::QUERETARO_OTOMI => 'Querétaro Otomi',
        self::ROMANIAN => 'Romanian',
        self::RUSSIAN => 'Russian',
        self::SERBIAN_CYRILLIC => 'Serbian (Cyrillic)',
        self::SERBIAN_LATIN => 'Serbian (Latin)',
        self::SLOVAK => 'Slovak',
        self::SLOVENIAN => 'Slovenian',
        self::SPANISH => 'Spanish',
        self::SWEDISH => 'Swedish',
        self::THAI => 'Thai',
        self::TURKISH => 'Turkish',
        self::UKRAINIAN => 'Ukrainian',
        self::URDU => 'Urdu',
        self::VIETNAMESE => 'Vietnamese',
        self::WELSH => 'Welsh',
        self::YUCATEC_MAYA => 'Yucatec Maya'
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
     * @return string
     */
    public function getEnglishName()
    {
        return $this->languages[$this->code];
    }
}
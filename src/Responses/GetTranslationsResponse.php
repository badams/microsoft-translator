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

namespace badams\MicrosoftTranslator\Responses;

use badams\MicrosoftTranslator\Language;

/**
 * Class GetTranslationsResponse
 * @package badams\MicrosoftTranslator\Responses
 * @link https://msdn.microsoft.com/en-us/library/ff512417.aspx
 */
class GetTranslationsResponse
{
    /**
     * @var Language
     */
    protected $from;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var TranslationMatch[]
     */
    protected $translations;

    /**
     * GetTranslationsResponse constructor.
     * @param $from
     * @param null $state
     */
    public function __construct($from, $state = null)
    {
        $this->from = new Language((string)$from);
        $this->state = $state;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return GetTranslationsResponse
     */
    public static function fromXml(\SimpleXMLElement $xml)
    {
        $instance = new GetTranslationsResponse($xml->From, $xml->State);

        foreach ($xml->Translations->TranslationMatch as $match) {
            $instance->addTranslation(TranslationMatch::fromXmlElement($match));
        }

        return $instance;
    }

    public function addTranslation(TranslationMatch $translation)
    {
        $this->translations[] = $translation;
    }

    /**
     * @return TranslationMatch[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return null|string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return Language
     */
    public function getFrom()
    {
        return $this->from;
    }
}

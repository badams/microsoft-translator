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

namespace badams\MicrosoftTranslator\Methods;

use badams\MicrosoftTranslator\Language;

/**
 * Class GetLanguageNames
 * @package badams\MicrosoftTranslator\Methods
 */
class GetLanguageNames implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    protected $locale;

    protected $languageCodes;


    public function __construct($locale, $languageCodes)
    {
        $this->locale = new Language($locale);
        $this->languageCodes = $languageCodes;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return 'POST';
    }

    /**
     * @return array
     */
    public function getRequestOptions()
    {
        return [
            'query' => ['locale' => $this->locale],
            'body' => $this->createBody(),
        ];
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return Language[]
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        $xml = simplexml_load_string($response->getBody()->getContents());
        $languages = [];

        foreach ($xml->string as $language) {
            $language = (string)$language;
            if (!empty($language)) {
                $languages[] = $language;
            }
        }

        return $languages;
    }

    /**
     * @return string
     */
    private function createBody()
    {
        $xml = new \DOMDocument();
        $array = $xml->createElementNS('http://schemas.microsoft.com/2003/10/Serialization/Arrays', 'ArrayOfstring');

        foreach ($this->languageCodes as $code) {
            $array->appendChild($xml->createElement('string', $code));
        }

        $xml->appendChild($array);
        return $xml->saveXML();
    }
}

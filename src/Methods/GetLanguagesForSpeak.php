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
 * Class GetLanguagesForSpeak
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512415.aspx
 */
class GetLanguagesForSpeak implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return 'GET';
    }

    /**
     * @return array
     */
    public function getRequestOptions()
    {
        return [];
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
           $languages[] = (string) $language;
        }

        return $languages;
    }
}
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
 * Class Detect
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512411.aspx
 */
class Detect implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * Detect constructor.
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

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
        return ['query' => ['text' => $this->text]];
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return Language
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        $xml = (array)simplexml_load_string($response->getBody()->getContents());
        return new Language((string)$xml[0]);
    }


}
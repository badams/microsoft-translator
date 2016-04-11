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
use badams\MicrosoftTranslator\Exceptions\ArgumentException;

/**
 * Class Detect
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512411.aspx
 */
class Detect implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    /**
     * @const Maximum allowable length of text
     */
    const TEXT_MAX_LENGTH = 10000;

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

        if (strlen($this->text) > Detect::TEXT_MAX_LENGTH) {
            throw new ArgumentException(
                sprintf('The length of the text must not exceed %s characters.', Detect::TEXT_MAX_LENGTH)
            );
        }
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

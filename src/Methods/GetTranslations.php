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

use badams\MicrosoftTranslator\Exceptions\ArgumentException;
use badams\MicrosoftTranslator\Exceptions\UnsupportedLanguageException;
use badams\MicrosoftTranslator\Language;
use badams\MicrosoftTranslator\Responses\GetTranslationsResponse;
use badams\MicrosoftTranslator\TranslateOptions;


/**
 * Class GetTranslations
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512417.aspx
 */
class GetTranslations implements \badams\MicrosoftTranslator\ApiMethodInterface
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
     * @var Language
     */
    protected $to;

    /**
     * @var Language|null
     */
    protected $from;

    /**
     * @var int
     */
    protected $maxTranslations;

    /**
     * @var TranslateOptions
     */
    protected $options;

    /**
     * GetTranslations constructor.
     * @param $text
     * @param $to
     * @param $from
     * @param int $maxTranslations
     * @param TranslateOptions|null $options
     * @throws ArgumentException
     * @throws UnsupportedLanguageException
     */
    public function __construct($text, $to, $from, $maxTranslations = 5, TranslateOptions $options = null)
    {
        $this->text = $text;
        $this->to = new Language($to);
        $this->from = new Language($from);
        $this->maxTranslations = $maxTranslations;
        $this->options = $options ? $options : new TranslateOptions();

        if (strlen($text) > GetTranslations::TEXT_MAX_LENGTH) {
            throw new ArgumentException(
                sprintf('The length of the text must not exceed %s characters.', GetTranslations::TEXT_MAX_LENGTH)
            );
        }
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
            'body' => (string)$this->options,
            'query' => [
                'text' => $this->text,
                'to' => (string)$this->to,
                'from' => (string)$this->from,
                'maxTranslations' => $this->maxTranslations,
            ]
        ];
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return GetTranslationsResponse
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        $xml = simplexml_load_string($response->getBody()->getContents());

        return GetTranslationsResponse::fromXml($xml);
    }
}
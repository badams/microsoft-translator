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
use badams\MicrosoftTranslator\Language;

/**
 * Class Translate
 *
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512421.aspx
 */
class Translate implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    /**
     * @const Maximum allowable length of text
     */
    const TEXT_MAX_LENGTH = 10000;

    /**
     * @const Html content type, HTML needs to be well-formed.
     */
    const CONTENT_TYPE_HTML = 'text/html';

    /**
     * @const plain text content type
     */
    const CONTENT_TYPE_PLAIN = 'text/plain';

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
     * @var string
     */
    protected $contentType;

    /**
     * Translate constructor.
     * @param $text
     * @param $to
     * @param null $from
     * @param string $contentType
     */
    public function __construct($text, $to, $from = null, $contentType = Translate::CONTENT_TYPE_PLAIN)
    {
        $this->text = $text;
        $this->to = new Language($to);

        if ($from !== null) {
            $this->from = new Language($from);
        }

        if (!in_array($contentType, [Translate::CONTENT_TYPE_PLAIN, Translate::CONTENT_TYPE_HTML])) {
            throw new ArgumentException(sprintf('%s is not a valid content type.', $contentType));
        }

        if (strlen($text) > Translate::TEXT_MAX_LENGTH) {
            throw new ArgumentException(
                sprintf('The length of the text must not exceed %s characters.', Translate::TEXT_MAX_LENGTH)
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
        return [
            'query' => [
                'text' => $this->text,
                'to' => (string)$this->to,
                'from' => (string)$this->from,
                'contentType' => $this->contentType,
            ]
        ];
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return string
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        $xml = (array)simplexml_load_string($response->getBody()->getContents());
        return (string)$xml[0];
    }
}

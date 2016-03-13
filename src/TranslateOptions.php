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

use badams\MicrosoftTranslator\Exceptions\ArgumentException;

/**
 * Class TranslateOptions
 * @package badams\MicrosoftTranslator
 */
class TranslateOptions
{
    /**
     * @const string
     */
    const XML_NAMESPACE_URI = 'http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2';

    /**
     * @const Html content type, HTML needs to be well-formed.
     */
    const CONTENT_TYPE_HTML = 'text/html';

    /**
     * @const plain text content type
     */
    const CONTENT_TYPE_PLAIN = 'text/plain';

    /**
     * @var string;
     */
    protected $category;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $reservedFlag;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $user;

    /**
     * TranslateOptions constructor.
     * @param $category
     * @param $contentType
     * @param $reservedType
     * @param $state
     * @param $uri
     * @param $user
     */
    public function __construct(
        $category = 'general',
        $contentType = TranslateOptions::CONTENT_TYPE_PLAIN,
        $state = null,
        $uri = null,
        $user = null,
        $reservedFlag = null
    )
    {
        if ($contentType !== self::CONTENT_TYPE_PLAIN) {
            throw new ArgumentException(sprintf('%s is not a valid content type.', $contentType));
        }

        $this->category = $category;
        $this->contentType = $contentType;
        $this->state = $state;
        $this->uri = $uri;
        $this->user = $user;
        $this->reservedFlag = $reservedFlag;
    }

    /**
     * @param string $root
     * @return \DOMDocument
     */
    public function xml($root = 'TranslateOptions')
    {
        $xml = new \DOMDocument();
        $options = $xml->createElementNS(self::XML_NAMESPACE_URI, $root);

        $options->appendChild($xml->createElement('Category', $this->category));
        $options->appendChild($xml->createElement('ContentType', $this->contentType));
        $options->appendChild($xml->createElement('ReservedFlag', $this->reservedFlag));
        $options->appendChild($xml->createElement('State', $this->state));
        $options->appendChild($xml->createElement('Uri', $this->uri));
        $options->appendChild($xml->createElement('User', $this->user));

        $xml->appendChild($options);

        return $xml;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->xml()->saveXML();
    }
}
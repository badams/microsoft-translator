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
 * Class GetTranslationsArray
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512418.aspx
 */
class GetTranslationsArray implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    const MAX_TEXTS = 10;

    /**
     * @const Maximum allowable length of text
     */
    const TEXT_MAX_LENGTH = 10000;

    /**
     * @var array
     */
    protected $texts;

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
     * GetTranslationsArray constructor.
     * @param $texts
     * @param $to
     * @param $from
     * @param int $maxTranslations
     * @param TranslateOptions|null $options
     * @throws ArgumentException
     * @throws UnsupportedLanguageException
     */
    public function __construct($texts, $to, $from, $maxTranslations = 5, TranslateOptions $options = null)
    {
        $this->texts = $texts;
        $this->to = new Language($to);
        $this->from = new Language($from);
        $this->maxTranslations = $maxTranslations;
        $this->options = $options ? $options : new TranslateOptions();

        $this->assertTextsIsArray();
        $this->assertTextsCount();
        $this->assertTextsLength();
    }

    /**
     * @throws ArgumentException
     */
    protected function assertTextsIsArray()
    {
        if (!is_array($this->texts)) {
            throw new ArgumentException('texts must be an array');
        }
    }

    /**
     * @throws ArgumentException
     */
    protected function assertTextsCount()
    {
        if (count($this->texts) > self::MAX_TEXTS) {
            throw new ArgumentException(sprintf('Amount of texts cannot exceed %s', self::MAX_TEXTS));
        }
    }

    /**
     * @throws ArgumentException
     */
    protected function assertTextsLength()
    {
        $textLengths = array_map('strlen', $this->texts);

        if (array_sum($textLengths) > self::TEXT_MAX_LENGTH) {
            throw new ArgumentException(sprintf('Total length of texts cannot exceed %s', self::TEXT_MAX_LENGTH));
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
        return ['body' => $this->createBodyXml()];
    }

    /**
     * @return string
     */
    protected function createBodyXml()
    {
        $xml = new \DOMDocument();
        $xml->formatOutput = true;
        $root = $xml->createElement('GetTranslationsArrayRequest');
        $xml->appendChild($root);

        $root->appendChild($xml->createElement('AppId'));
        $root->appendChild($xml->createElement('From', $this->from));

        $this->appendOptionsNode($xml, $root);
        $this->appendTextsNode($xml, $root);
        
        $root->appendChild($xml->createElement('To', $this->to));
        $root->appendChild($xml->createElement('MaxTranslations', $this->maxTranslations));

        return $xml->saveXML();
    }

    protected function appendTextsNode(\DOMDocument $xml, \DOMNode $root)
    {
        $texts = $xml->createElement('Texts');
        $root->appendChild($texts);

        foreach ($this->texts as $text) {
            $texts->appendChild(
                $xml->createElementNS('http://schemas.microsoft.com/2003/10/Serialization/Arrays', 'string', $text)
            );
        }
    }

    protected function appendOptionsNode(\DOMDocument $xml, \DOMNode $root)
    {
        $node = $this->options->xml('Options')->childNodes->item(0);
        $options = $xml->importNode($node, true);
        $root->appendChild($options);
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return GetTranslationsResponse[]
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        $xml = simplexml_load_string($response->getBody()->getContents());

        $responses = [];

        foreach ($xml->GetTranslationsResponse as $node) {
            $responses[] = GetTranslationsResponse::fromXml($node);
        }

        return $responses;
    }

}
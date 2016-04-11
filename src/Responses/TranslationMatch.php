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

/**
 * Class TranslationMatch
 * @package badams\MicrosoftTranslator\Responses
 * @link https://msdn.microsoft.com/en-us/library/ff512417.aspx
 */
class TranslationMatch
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $matchDegree;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var string
     */
    protected $translatedText;

    /**
     * @var string
     */
    protected $matchedOriginalText;

    /**
     * TranslationMatch constructor.
     * @param $count
     * @param $matchDegree
     * @param $rating
     * @param $translatedText
     */
    public function __construct($count, $matchDegree, $rating, $translatedText, $matchedOriginalText = null, $error = null)
    {
        $this->count = (int)$count;
        $this->matchDegree = (int)$matchDegree;
        $this->rating = (int)$rating;
        $this->translatedText = $translatedText;
        $this->matchedOriginalText = $matchedOriginalText;
        $this->error = $error;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return TranslationMatch
     */
    public static function fromXmlElement(\SimpleXMLElement $xml)
    {
        return new TranslationMatch(
            (string)$xml->Count,
            (string)$xml->MatchDegree,
            (string)$xml->Rating,
            (string)$xml->TranslatedText,
            (string)$xml->MatchedOriginalText
        );
    }

    /**
     * @return string
     */
    public function getTranslatedText()
    {
        return $this->translatedText;
    }

    /**
     * @return int
     */
    public function getMatchDegree()
    {
        return $this->matchDegree;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return null|string
     */
    public function getError()
    {
        return $this->error;
    }

    public function getMatchedOriginalText()
    {
        return $this->matchedOriginalText;
    }
}

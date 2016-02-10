<?php

namespace badams\MicrosoftTranslator\Methods;

/**
 * Class Speak
 * @package badams\MicrosoftTranslator\Methods
 * @link https://msdn.microsoft.com/en-us/library/ff512420.aspx
 */
class Speak implements \badams\MicrosoftTranslator\ApiMethodInterface
{
    const FORMAT_MP3 = 'audio/mp3';
    const FORMAT_WAV = 'audio/wav';

    const OPTION_MAX_QUALITY = 'MaxQuality';
    const OPTION_MIN_SIZE = 'MinSize';

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $options;

    /**
     * Speak constructor.
     * @param $text
     * @param $language
     * @param string $format
     * @param string $options
     */
    public function __construct($text, $language, $format = Speak::FORMAT_MP3, $options = Speak::OPTION_MAX_QUALITY)
    {
        $this->text = $text;
        $this->language = $language;
        $this->format = $format;
        $this->options = $options;
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
        return ['query' => [
            'text' => $this->text,
            'language' => $this->language,
            'format' => $this->format,
            'options' => $this->options,
        ]];
    }

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return string
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response)
    {
        return base64_encode($response->getBody()->getContents());
    }


}
<?php

namespace badams\MicrosoftTranslator\Methods;

use badams\MicrosoftTranslator\ApiMethodInterface;

/**
 * Class Translate
 * @package badams\MicrosoftTranslator\Methods
 */
class Translate implements ApiMethodInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string|null
     */
    protected $from;

    /**
     * Translate constructor.
     * @param $text
     * @param $to
     * @param null $from
     */
    public function __construct($text, $to, $from = null)
    {
        $this->text = $text;
        $this->to = $to;
        $this->from = $from;
    }

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
                'to' => $this->to,
                'from' => $this->from,
            ]
        ];
    }

    /**
     * @param \GuzzleHttp\Message\Response $response
     * @return string
     */
    public function processResponse(\GuzzleHttp\Message\Response $response)
    {
        $xml = (array)simplexml_load_string($response->getBody()->getContents());
        return (string)$xml[0];
    }


}
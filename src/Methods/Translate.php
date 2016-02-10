<?php

namespace badams\MicrosoftTranslator\Methods;

/**
 * Class Translate
 * @package badams\MicrosoftTranslator\Methods
 */
class Translate implements \badams\MicrosoftTranslator\ApiMethodInterface
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
                'to' => $this->to,
                'from' => $this->from,
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
<?php

namespace badams\MicrosoftTranslator;

/**
 * Interface ApiMethodInterface
 * @package badams\MicrosoftTranslator
 */
interface ApiMethodInterface
{
    /**
     * @return string
     */
    public function getRequestMethod();

    /**
     * @return array
     */
    public function getRequestOptions();

    /**
     * @param \GuzzleHttp\Message\ResponseInterface $response
     * @return mixed
     */
    public function processResponse(\GuzzleHttp\Message\ResponseInterface $response);
}
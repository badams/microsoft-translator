<?php
namespace badams\MicrosoftTranslator;

use badams\MicrosoftTranslator\Exceptions\ArgumentException;
use badams\MicrosoftTranslator\Exceptions\AuthException;
use badams\MicrosoftTranslator\Exceptions\QuotaExceededException;
use badams\MicrosoftTranslator\Exceptions\RecoverableException;
use badams\MicrosoftTranslator\Exceptions\TokenExpiredException;
use badams\MicrosoftTranslator\Exceptions\TranslatorException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class MicrosoftTranslator
 * @package badams\MicrosoftTranslator
 */
class MicrosoftTranslator
{
    /**
     *
     */
    const AUTH_URL = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13';

    /**
     *
     */
    const BASE_URL = 'http://api.microsofttranslator.com/V2/Http.svc/';

    /**
     * @var Client
     */
    private $http;

    /**
     * @var string
     */
    private $scope = 'http://api.microsofttranslator.com';

    /**
     * @var string
     */
    private $grantType = 'client_credentials';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * MicrosoftTranslator constructor.
     */
    public function __construct(\GuzzleHttp\ClientInterface $httpClient = null)
    {
        if (is_null($httpClient)) {
            $httpClient = new \GuzzleHttp\Client();
        }

        $this->http = $httpClient;
    }

    /**
     * @param $id
     * @param $secret
     */
    public function setClient($id, $secret)
    {
        $this->clientId = $id;
        $this->clientSecret = $secret;
    }

    /**
     * @return mixed
     * @throws AuthException
     */
    private function getAccessToken()
    {
        if (!$this->accessToken) {
            $params = array_merge([
                'grant_type' => $this->grantType,
                'scope' => $this->scope,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]);

            try {
                $response = $this->http->post(self::AUTH_URL, ['body' => $params]);
                $result = json_decode((string)$response->getBody());
            } catch (RequestException $e) {
                $result = json_decode((string)$e->getResponse()->getBody());
                throw new AuthException($result->error_description);
            }

            $this->accessToken = $result->access_token;
        }

        return $this->accessToken;
    }

    /**
     * @param $action
     * @param $params
     * @param string $method
     * @return null|string
     * @throws ArgumentException
     * @throws AuthException
     */
    private function request($action, $params, $method = 'GET')
    {
        $request = $this->http->createRequest($method, self::BASE_URL . $action, [
            'exceptions' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'text/xml',
            ],
            'query' => $params,
        ]);

        $response = $this->http->send($request);
        $result = (string)$response->getBody();

        if ($response->getStatusCode() == 200) {
            $xml = (array)simplexml_load_string($result);
            return (string)$xml[0];
        }

        try {
            $this->processError(strip_tags($result));
        } catch (TokenExpiredException $e) {
            $this->accessToken = null;
            return $this->request($action, $params, $method);
        }

        throw new TranslatorException($result);
    }

    /**
     * @param string $message
     * @throws ArgumentException
     * @throws QuotaExceededException
     * @throws TokenExpiredException
     * @throws TranslatorException
     */
    private function processError($message)
    {
        if (strpos($message, 'Argument Exception') === 0 && strpos($message, 'The incoming token has expired.')) {
            throw new TokenExpiredException($message);
        }

        if (strpos($message, 'TranslateApiException') === 0 && strpos($message, 'credentials has zero balance.')) {
            throw new QuotaExceededException($message);
        }

        if (strpos($message, 'Argument Exception') === 0) {
            throw new ArgumentException($message);
        }
    }

    /**
     * @param $text
     * @param $to
     * @param $from
     * @return mixed
     */
    public function translate($text, $to, $from)
    {
        return $this->request('Translate', [
            'to' => $to,
            'from' => $from,
            'text' => $text,
        ]);
    }
}
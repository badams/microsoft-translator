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
use badams\MicrosoftTranslator\Exceptions\AuthException;
use badams\MicrosoftTranslator\Exceptions\QuotaExceededException;
use badams\MicrosoftTranslator\Exceptions\TokenExpiredException;
use badams\MicrosoftTranslator\Exceptions\TranslatorException;
use badams\MicrosoftTranslator\Methods\Detect;
use badams\MicrosoftTranslator\Methods\GetLanguageNames;
use badams\MicrosoftTranslator\Methods\GetLanguagesForSpeak;
use badams\MicrosoftTranslator\Methods\GetLanguagesForTranslate;
use badams\MicrosoftTranslator\Methods\Speak;
use badams\MicrosoftTranslator\Methods\Translate;
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
     * @var \GuzzleHttp\ClientInterface
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
     * @param ApiMethodInterface $method
     * @return \GuzzleHttp\Message\Request|\GuzzleHttp\Message\RequestInterface
     * @throws AuthException
     */
    private function createRequest(ApiMethodInterface $method)
    {
        $reflection = new \ReflectionClass($method);

        return $this->http->createRequest(
            $method->getRequestMethod(),
            self::BASE_URL . $reflection->getShortName(),
            array_merge([
                'exceptions' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    'Content-Type' => 'text/xml',
                ],

            ], $method->getRequestOptions())
        );
    }

    /**
     * @param ApiMethodInterface $method
     * @return mixed
     * @throws ArgumentException
     * @throws QuotaExceededException
     * @throws TranslatorException
     */
    private function execute(ApiMethodInterface $method)
    {
        $response = $this->http->send($this->createRequest($method));

        if ($response->getStatusCode() != 200) {
            try {
                $message = strip_tags($response->getBody());
                $this->assertNoArgumentException($message);
                $this->assertNoTranslateExceptionAndZeroBalance($message);
            } catch (TokenExpiredException $e) {
                $this->accessToken = null;
                return $this->execute($method);
            }

            throw new TranslatorException($response->getBody());
        }

        return $method->processResponse($response);
    }

    /**
     * @param string $message
     * @throws QuotaExceededException
     */
    private function assertNoTranslateExceptionAndZeroBalance($message)
    {
        if (strpos($message, 'TranslateApiException') === 0
            && strpos($message, 'credentials has zero balance.')
        ) {
            throw new QuotaExceededException($message);
        }
    }

    /**
     * @param string $message
     * @throws ArgumentException
     * @throws TokenExpiredException
     */
    private function assertNoArgumentException($message)
    {
        if (strpos($message, 'Argument Exception') === 0) {
            if (strpos($message, 'The incoming token has expired.')) {
                throw new TokenExpiredException($message);
            }

            throw new ArgumentException($message);
        }
    }

    /**
     * @param $text
     * @param $to
     * @param $from
     * @return null|string
     */
    public function translate($text, $to, $from = null)
    {
        return $this->execute(new Translate($text, $to, $from));
    }

    /**
     * @param $text
     * @return null|Language
     * @throws TranslatorException
     */
    public function detect($text)
    {
        return $this->execute(new Detect($text));
    }

    /**
     * @param $text
     * @param $language
     * @param string $format
     * @param string $options
     * @return mixed
     * @throws TranslatorException
     */
    public function speak($text, $language, $format = Speak::FORMAT_MP3, $options = Speak::OPTION_MAX_QUALITY)
    {
        return $this->execute(new Speak($text, $language, $format, $options));
    }

    /**
     * @return Language[]
     * @throws TranslatorException
     */
    public function getLanguagesForSpeak()
    {
        return $this->execute(new GetLanguagesForSpeak());
    }

    /**
     * @param $languageCodes
     * @param string $locale
     * @return mixed
     * @throws TranslatorException
     */
    public function getLanguageNames($languageCodes, $locale = Language::ENGLISH)
    {
        return $this->execute(new GetLanguageNames($locale, $languageCodes));
    }

    /**
     * @return Language[]
     * @throws TranslatorException
     */
    public function getLanguagesForTranslate()
    {
        return $this->execute(new GetLanguagesForTranslate());
    }
}
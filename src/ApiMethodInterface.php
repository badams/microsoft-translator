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
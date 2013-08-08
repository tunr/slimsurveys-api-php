<?php

namespace SlimSurveys;

class Client
{
    /**
     * API base url
     *
     * @var string
     */
    const BASE_URL = 'https://slimsurveys.com/api/';

    /**
     * API key.
     *
     * @var string
     */
    private $key = '';

    /**
     * User authentication token.
     *
     * @var string
     */
    private $token = '';

    /**
     * Response data format.
     *
     * @var string
     */
    private $format = 'json';

    /**
     * Request headers.
     *
     * @var array
     */
    private $headers = array();

    /**
     * Default curl options.
     *
     * @var array
     */
    private $options = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_FAILONERROR    => false,
        //CURLOPT_VERBOSE        => true,
    );

    /**
     * Constructor.
     *
     * @param array $config configuration options
     *
     * @return void
     */
    public function __construct($config)
    {
        // validate params

        $this->key = (!empty($config['key'])) ? $config['key'] : '';

        // todo: make an array of accepted formats for validation
        $this->format = (!empty($config['format'])) ? $config['format'] : $this->format;

        if (!empty($config['token'])) {
            $this->token = $config['token'];
        }

    }

    // todo: add following methods
    // public function setFormat()
    // public function setToken()
    // public function setKey()
    // public function buildUrl()
    // public function getCode()
    // public function getInfo()
    // public function getResponse()

    /**
     * Add request header entry.
     *
     * @param $header header entry title
     * @param $content header entry content
     * @return SlimSurveys
     */
    public function addHeader($header, $content = null)
    {
        $this->headers[] = ($content) ? $header . ':' . $content : $header;

        return $this;
    }

    /**
     * Set curl config option.
     *
     * @param $code curl option code
     * @param $value curl option value
     * @return SlimSurveys
     */
    public function setOption($code, $value)
    {
        $this->options[$code] = $value;
        
        return $this;
    }

    /**
     * Get curl options
     *
     * @return void
     */
    public function getOptions()
    {        
        return $this->options;
    }

    /**
     * Get authorization token
     * 
     * @return json
     */
    public function getAuthToken($email, $password)
    {
        $credentials = array(
            'email'    => $email,
            'password' => $password,
        );

        return $this->request('auth/token', 'post', array(), $credentials);
    }

    /**
     * Make api request
     * 
     * @return json
     */
    public function request($url, $method = 'get', $params = array(), $post = array())
    {
        // todo: file upload
        // todo: may need to build oauth header

        $url = self::BASE_URL . trim($url, '/');

        if ('json' !== $this->format) {
            $params['format'] = $this->format;
        }

        // validate request params
        $params = (is_array($params)) ? $params : array();

        if ($params) {
            $url .= '?' . http_build_query($params, '', '&');
        }

        if ('POST' === strtoupper($method))
        {
            $this->setOption(CURLOPT_POST, true);
            $this->setOption(CURLOPT_POSTFIELDS, http_build_query($post, '', '&'));
        }

        $this->addHeader('X-API-KEY', $this->key);
        $this->setOption(CURLOPT_HTTPHEADER, $this->headers);
        $this->setOption(CURLOPT_URL, $url);

        $curl = curl_init();

        curl_setopt_array($curl, $this->getOptions());

        $this->response = curl_exec($curl);
        $this->info     = curl_getinfo($curl);

        if (false === $this->response) {
            $errorCode    = curl_errno($curl);
            $errorMessage = curl_error($curl);
            
            // todo: populate exception, close session, throw exception
            // throw new SlimSurveysApiException($errorMessage, $errorCode);
        }

        curl_close($curl);

        return $this->response;
    }

    /**
     * Debug curl request
     * 
     * @return void
     */
    public function debug()
    {
        var_dump($this->response);
        var_dump($this->info);
        var_dump($this->options);
        var_dump($this->headers);
    }
}

/*
 * Slim surveys api exception.
 */
class ApiException extends \Exception {
    // todo
}

/*
 * Slim surveys exception.
 */
if (!function_exists('curl_init')) {
    throw new Exception('SlimSurveys needs the CURL PHP extension.');
}

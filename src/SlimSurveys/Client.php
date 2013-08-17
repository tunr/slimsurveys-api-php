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
     * API key
     *
     * @var string
     */
    private $key = '';

    /**
     * User authentication token
     *
     * @var string
     */
    private $token = '';

    /**
     * Response data format
     *
     * @var string
     */
    private $format = 'json';

    /**
     * Request headers
     *
     * @var array
     */
    private $headers = array();

    /**
     * Dynamic curl options
     *
     * @var array
     */
    private $options = array();

    /**
     * Request curl options
     *
     * @var array
     */
    private $requestOptions = array();

    /**
     * Default curl options
     *
     * @var array
     */
    private $defaultOptions = array(
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_USERAGENT      => 'SlimSurveys-API-Client',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_FAILONERROR    => 0,
        CURLOPT_ENCODING       => 'utf-8',
    );

    /**
     * Constructor
     *
     * @param array $config configuration options
     *
     * @return void
     */
    public function __construct($config)
    {
        // todo: validate params
        // todo: make an array of accepted formats for validation

        $this->key    = (!empty($config['key'])) ? $config['key'] : '';
        $this->format = (!empty($config['format'])) ? $config['format'] : $this->format;

        if (!empty($config['token']))
        {
            $this->headers['X-AUTH-TOKEN'] = $config['token'];
        }
    }

    /**
     * Get authorization token
     * 
     * @return json
     */
    public function getAuthToken($email, $password)
    {
        $data = array(
            'email'    => $email,
            'password' => $password,
        );

        return $this->post('auth/token', $data);
    }

    /**
     * Get authenticated users account
     * 
     * @return json
     */
    public function me()
    {
        return $this->get('users/me');
    }

    // ===================================================================================

    /**
     * Build header
     *
     * @return array()
     */
    private function buildHeader()
    {
        $headers = array();

        foreach ($this->headers as $header => $content)
        {
            $headers[] = ($content) ? $header . ':' . $content : $header;
        }

        return $headers;
    }

    /**
     * Set header entry
     *
     * @param $header header entry title
     * @param $content header entry content
     * @return SlimSurveys
     */
    public function setHeader($header, $content = null)
    {
        $this->headers[$header] = $content;

        return $this;
    }

    /**
     * Set curl config option
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
     * Set curl config options
     *
     * @param $options curl option code
     * @return SlimSurveys
     */
    public function setOptions($options = array())
    {
        foreach ($options as $code => $value) {
            $this->setOption($code, $value);
        }
        
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
     * Build complete url
     * 
     * @return string
     */
    private function buildUrl($url, $params)
    {
        $url = self::BASE_URL . trim($url, '/');

        if ('json' !== $this->format) {
            $params['format'] = $this->format;
        }

        if (is_array($params) && !empty($params)) {
            $url .= '?' . http_build_query($params, '', '&');
        }

        $this->setOption(CURLOPT_URL, $url);

        return $url;
    }

    /**
     * Get request
     * 
     * @return void
     */
    private function get($url, $data = array())
    {
        $this->setup();
        //$this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->requestOptions[CURLOPT_RETURNTRANSFER] = 1;

        return $this->request($url, $data);
    }

    /**
     * Post request
     * 
     * @return void
     */
    private function post($url, $data = array())
    {
        $this->setup();
        //$this->setOption(CURLOPT_POST, 1);
        $this->requestOptions[CURLOPT_POST] = 1;
        $this->setPostFields($data);

        return $this->request($url);
    }

    /**
     * Set post fields
     * 
     * @return void
     */
    private function setPostFields($data)
    {
        if (is_array($data)) {
            //$this->setOption(CURLOPT_POSTFIELDS, $data);
            $this->requestOptions[CURLOPT_POSTFIELDS] = $data;
        }
    }

    /**
     * Delete request
     * 
     * @return void
     */
    private function delete($url, $data = array())
    {
        $this->setup();
        $this->requestOptions[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $this->setPostFields($data);
        
        return $this->request($url);
    }

    /**
     * Put request
     * 
     * @return void
     */
    private function put($url, $data = array())
    {
        $this->setup();

        //$this->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->requestOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $this->setPostFields($data);
        //$this->setUrl($url);
        
        return $this->request($url);
    }

    /**
     * Setup request
     * 
     * @return void
     */
    private function setup()
    {
        $this->response       = '';
        $this->info           = array();
        $this->headers        = array();
        $this->options        = $this->defaultOptions;
        $this->requestOptions = array();
        //$this->errorCode    = 0;
        //$this->errorString  = '';
    }

    /**
     * Make api request
     * 
     * @return json
     */
    public function request($url, $params = array())
    {
        // todo: file upload
        // todo: may need to build oauth header

        $this->setOptions($this->requestOptions);

        $this->buildUrl($url, $params);
        $this->setHeader('X-API-KEY', $this->key);
        $this->setOption(CURLOPT_HTTPHEADER, $this->buildHeader());

        $curl = curl_init();

        curl_setopt_array($curl, $this->getOptions());

        $this->response = curl_exec($curl);
        $this->info     = curl_getinfo($curl);

        if (false === $this->response) {
            $errorCode    = curl_errno($curl);
            $errorMessage = curl_error($curl);
            
            //var_dump($errorCode);
            //var_dump($errorMessage);

            // todo: populate exception, close session, throw exception
            // throw new SlimSurveysApiException($errorMessage, $errorCode);
        }

        curl_close($curl);

        return $this->response;
    }

}

/*
 * Slim surveys api exception.
 */
class ApiException extends \Exception {
    // todo: integrate exception handling
}

/*
 * Slim surveys exception.
 */
if (!function_exists('curl_init')) {
    throw new Exception('SlimSurveys needs the CURL PHP extension.');
}

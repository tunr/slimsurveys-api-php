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
     * Url path
     *
     * @var string
     */
    private $url = '';

    /**
     * Headers
     *
     * @var array
     */
    private $headers = array();

    /**
     * Options
     *
     * @var array
     */
    private $options = array();

    /**
     * Query parameters
     *
     * @var array
     */
    private $queries = array();

    /**
     * Post fields
     *
     * @var array
     */
    private $fields = array();

    /**
     * Response data
     *
     * @var array
     */
    private $data = null;

    /**
     * Curl info
     *
     * @var array
     */
    private $info = array();

    /**
     * Constructor
     *
     * @param array $config configuration options
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->key = (!empty($config['key'])) ? (string) $config['key'] : '';

        if (!empty($config['token']))
        {
            $this->token = (string) $config['token'];
        }
    }

// ===================================================================================
// auth
// ===================================================================================

    /**
     * Get authorization token
     * 
     * @return $this
     */
    public function getAuthToken($email, $password)
    {
        $data = array(
            'email'    => $email,
            'password' => $password,
        );

        return $this->setUrl('auth/token')
            ->setFields($data)
            ->post();
    }

// ===================================================================================
// answers
// ===================================================================================

    // get survey answers
    public function getSurveyAnswers($surveyId, $uvid = null)
    {
        $this->setUrl('answers/survey')->applyResourceId('survey_uid', $surveyId);

        return $this->setField('uvid', $uvid)->get();
    }

    // get question answers
    public function getQuestionAnswers($questionId)
    {
        return $this->setUrl('answers/question/' . $questionId)->get();
    }

    // create question answer
    public function createQuestionAnswer($questionId, $answer, $milestone = '', $uvid = '')
    {
        $this->setUrl('answers/question/' . $questionId);
        $this->setField('answer', $answer);
        $this->setField('milestone', $milestone);
        $this->setField('uvid', $uvid);

        return $this->post();
    }

// ===================================================================================
// identities
// ===================================================================================

    // create identity
    public function createIdentity($surveyId, $uvid, $data)
    {
        $this->setUrl('answers/question')->applyResourceId('survey_uid', $surveyId);
        $this->setField('uvid', $uvid);
        $this->setFields($data);

        return $this->post();
    }

// ===================================================================================
// images
// ===================================================================================

    // get question answers
    public function getImage($imageId)
    {
        return $this->setUrl('images/image/' . $imageId)->get();
    }

    // create question image
    public function createQuestionImage($questionId, $file, $position = 0)
    {
        $this->setUrl('images/create/' . $questionId);
        $this->setField('file', '@' . $file);
        $this->setField('position', $position);

        return $this->post();
    }

    // delete image
    public function deleteImage($imageId)
    {
        return $this->setUrl('images/image/' . $imageId)->delete();
    }

// ===================================================================================
// questions
// ===================================================================================

    // get question
    public function getQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->get();
    }

    // get question answers
    public function createSurveyQuestion($surveyId)
    {
        return $this->setUrl('questions/create/' . $surveyId)->post();
    }

    // get question answers
    public function updateQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->post();
    }

    // delete question
    public function deleteQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->delete();
    }

// ===================================================================================
// results
// ===================================================================================

    // get survey results
    public function getSurveyResults($surveyId)
    {
        $this->setUrl('results/survey')->applyResourceId('survey_uid', $surveyId);

        return $this->get();
    }

    // get question results
    public function getQuestionResults($questionId)
    {
        return $this->setUrl('results/question/' . $questionId)->get();
    }

// ===================================================================================
// surveys
// ===================================================================================

    // get survey
    public function getSurvey($surveyId)
    {
        $this->setUrl('surveys/survey')->applyResourceId('survey_uid', $surveyId);

        return $this->get();
    }

    // get survey embed
    public function getSurveyEmbed($surveyId)
    {
        $this->setUrl('surveys/embed')->applyResourceId('survey_uid', $surveyId);

        return $this->get();
    }

    // get user surveys
    public function getUserSurveys($userId)
    {
        $this->setUrl('surveys/user')->applyResourceId('username', $userId);

        return $this->get();
    }

    // get my surveys
    public function getMySurveys()
    {
        return $this->setUrl('surveys/mine')->get();
    }

// ===================================================================================
// users
// ===================================================================================

    // Get authenticated users account
    public function me()
    {
        return $this->setUrl('users/me')->get();
    }

// ===================================================================================
// request methods
// ===================================================================================

    /**
     * Set url
     * 
     * @return $this
     */
    public function setUrl($url)
    {
        $this->reset();

        $this->url = $url;

        return $this;
    }

    /**
     * Determine request resource id placement
     * 
     * @return $this
     */
    public function applyResourceId($query, $resourceId)
    {
        if (is_string($resourceId))
        {
            $this->setQuery($query, $resourceId);
        }
        else
        {
            $url  = trim($this->url, '/');
            $url .= '/' . $resourceId;

            $this->url = $url;
        }

        return $this;
    }

    /**
     * Build complete url
     * 
     * @return string
     */
    private function buildUrl()
    {
        $url     = self::BASE_URL . trim($this->url, '/');
        $queries = $this->queries;

        if (!empty($queries)) {
            $url .= '?' . http_build_query($queries, '', '&');
        }

        $this->setOption(CURLOPT_URL, $url);

        return $url;
    }

    /**
     * Build header
     *
     * @return array()
     */
    private function buildHeader()
    {
        $this->headers['X-API-KEY'] = $this->key;

        if ($this->token)
        {
            $this->headers['X-AUTH-TOKEN'] = $this->token;
        }

        foreach ($this->headers as $header => $content)
        {
            $headers[] = ($content) ? $header . ':' . $content : $header;
        }

        $this->setOption(CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    /**
     * Set header entry
     *
     * @param $key key
     * @param $value value
     * @return SlimSurveys
     */
    private function setHeader($key, $value = null)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set config option
     *
     * @param $key key
     * @param $value value
     * @return SlimSurveys
     */
    private function setOption($key, $value)
    {
        $this->options[$key] = $value;
        
        return $this;
    }

    /**
     * Set query parameter
     *
     * @param $key key
     * @param $value value
     * @return SlimSurveys
     */
    private function setQuery($key, $value)
    {
        $this->queries[$key] = $value;
        
        return $this;
    }

    /**
     * Set query parameters
     *
     * @param $queries query parameters
     * @return SlimSurveys
     */
    private function setQueries($queries = array())
    {
        foreach ($queries as $key => $value)
        {
            $this->setQuery($key, $value);
        }
        
        return $this;
    }

    /**
     * Set post field
     *
     * @param $key key
     * @param $value value
     * @return SlimSurveys
     */
    private function setField($key, $value)
    {
        $this->fields[$key] = $value;
        
        return $this;
    }

    /**
     * Set post fields
     *
     * @param $fields post fields
     * @return SlimSurveys
     */
    private function setFields($fields = array())
    {
        foreach ($fields as $key => $value) {
            $this->setField($key, $value);
        }

        return $this;
    }

    /**
     * Get request
     * 
     * @return void
     */
    private function get()
    {
        return $this->request();
    }

    /**
     * Post request
     * 
     * @return void
     */
    private function post()
    {
        $this->setOption(CURLOPT_POST, 1);
        $this->setPostFields();

        return $this->request();
    }

    /**
     * Set post fields
     * 
     * @return void
     */
    private function setPostFields()
    {
        if (!empty($this->fields))
        {
            $this->setOption(CURLOPT_POSTFIELDS, $this->fields);
        }

        return $this;
    }

    /**
     * Delete request
     * 
     * @return void
     */
    private function delete()
    {
        $this->setPostFields();
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->request();
    }

    /**
     * Put request
     * 
     * @return void
     */
    private function put()
    {
        $this->setPostFields();
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');

        return $this->request();
    }

    /**
     * Reset request settings
     * 
     * @return void
     */
    private function reset()
    {
        $this->options = array(
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_FAILONERROR    => 0,
            CURLOPT_ENCODING       => 'utf-8',
            CURLOPT_USERAGENT      => 'SlimSurveys-API-Client',
        );

        $this->response = '';
        $this->info     = array();
        $this->headers  = array();
        $this->queries  = array();
        $this->fields   = array();
        
        return $this;
    }

    /**
     * Make api request
     * 
     * @return json
     */
    private function request()
    {
        $this->buildUrl();
        $this->buildHeader();

        $curl = curl_init();

        curl_setopt_array($curl, $this->options);

        $this->response = curl_exec($curl);
        $this->info     = curl_getinfo($curl);

        if (false !== $this->response)
        {
            $this->data = json_decode($this->response);
        }

        $this->info['error_code']    = curl_errno($curl);
        $this->info['error_message'] = curl_error($curl);

        curl_close($curl);

        return $this;
    }

    /**
     * Make api request
     * 
     * @return json
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get raw response
     * 
     * @return json
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get request info
     * 
     * @return array|string
     */
    public function getInfo($key = null)
    {
        if (isset($key))
        {
            return (array_key_exists($key, $this->info)) ? $this->info[$key] : null;
        }

        return $this->info;
    }

    /**
     * Get http response code
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->getInfo('http_code');
    }
}

/*
 * Slim surveys exception.
 */
if (!function_exists('curl_init')) {
    throw new Exception('SlimSurveys API client requires the CURL PHP extension.');
}

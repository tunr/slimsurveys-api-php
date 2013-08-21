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
        return $this->setUrl('answers/survey/' . $surveyId)
            ->setField('uvid', $uvid)
            ->get();
    }

    // get survey answers by uid
    public function getSurveyAnswersByUid($surveyId, $uvid = null)
    {
        return $this->setUrl('answers/survey')
            ->setQuery('survey_uid', $surveyId)
            ->setField('uvid', $uvid)
            ->get();
    }

    // get question answers
    public function getQuestionAnswers($questionId)
    {
        return $this->setUrl('answers/question/' . $questionId)->get();
    }

    // create question answer
    public function createQuestionAnswer($questionId, $answer, $milestone = '', $uvid = '')
    {
        return $this->setUrl('answers/question/' . $questionId)
            ->setField('answer', $answer)
            ->setField('milestone', $milestone)
            ->setField('uvid', $uvid);

        return $this->post();
    }

// ===================================================================================
// identities
// ===================================================================================

    // create identity
    public function createSurveyIdentity($surveyId, $uvid, $data)
    {
        return $this->setUrl('answers/question/' . $surveyId)
            ->setField('uvid', $uvid)
            ->setFields($data)
            ->post();
    }

    // create survey identity by uid
    public function createSurveyIdentityByUid($surveyId, $uvid, $data)
    {
        return $this->setUrl('answers/question')
            ->setQuery('survey_uid', $surveyId)
            ->setField('uvid', $uvid)
            ->setFields($data)
            ->post();
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
        return $this->setUrl('images/create/' . $questionId)
            ->setField('file', '@' . $file)
            ->setField('position', $position)
            ->post();
    }

    // delete image
    public function deleteImage($imageId)
    {
        return $this->setUrl('images/image/' . $imageId)->delete();
    }

// ===================================================================================
// options
// ===================================================================================

    public function getOption($optionId)
    {
        return $this->setUrl('options/option/' . $optionId)->get();
    }

    public function createQuestionOption($questionId, $value = null, $position = 0)
    {
        return $this->setUrl('options/create/' . $questionId)
            ->setField('value', $value)
            ->setField('position', $position)
            ->post();
    }

    public function updateOption($optionId, $value, $position = 0)
    {
        return $this->setUrl('options/option/' . $optionId)
            ->setField('value', $value)
            ->setField('position', $position)
            ->post();
    }

    public function deleteOption($optionId)
    {
        return $this->setUrl('options/option/' . $optionId)->delete();
    }

// ===================================================================================
// questions
// ===================================================================================

    // get question
    public function getQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->get();
    }

    // create survey question
    public function createSurveyQuestion($surveyId, $type, $text = '', $position = 0)
    {
        return $this->setUrl('questions/create/' . $surveyId)
            ->setField('type', $type)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
    }

    // create survey question by uid
    public function createSurveyQuestionByUid($surveyId, $type, $text = '', $position = 0)
    {
        return $this->setUrl('questions/create')
            ->setQuery('survey_uid', $surveyId)
            ->setField('type', $type)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
    }

    // update question
    public function updateQuestion($questionId, $text = '', $position = 0)
    {
        return $this->setUrl('questions/question/' . $questionId)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
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
        return $this->setUrl('results/survey/' . $surveyId)->get();
    }

    // get survey results
    public function getSurveyResultsByUid($surveyId)
    {
        return $this->setUrl('results/survey')
            ->setQuery('survey_uid', $surveyId)
            ->get();
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
        return $this->setUrl('surveys/survey/' . $surveyId)->get();
    }

    // get survey
    public function getSurveyByUid($surveyId)
    {
        return $this->setUrl('surveys/survey')
            ->setQuery('survey_uid', $surveyId)
            ->get();
    }

    // get survey embed
    public function getSurveyEmbed($surveyId)
    {
        return $this->setUrl('surveys/embed/' . $surveyId)->get();
    }

    // get survey embed
    public function getSurveyEmbedByUid($surveyId)
    {
        return $this->setUrl('surveys/embed')
            ->setQuery('survey_uid', $surveyId)
            ->get();
    }

    // get user surveys
    public function getUserSurveys($userId)
    {
        return $this->setUrl('surveys/user/' . $userId)->get();
    }

    // get user surveys
    public function getUserSurveysByUsername($userId)
    {
        return $this->setUrl('surveys/user')
            ->setQuery('username', $userId)
            ->get();
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
     * @param  string $url resource url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->reset();

        $this->url = $url;

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

        if (!empty($queries))
        {
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
        foreach ($fields as $key => $value)
        {
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
        return $this->setOption(CURLOPT_POST, 1)
            ->setPostFields()
            ->request();
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
        return $this->setPostFields()
            ->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE')
            ->request();
    }

    /**
     * Put request
     * 
     * @return void
     */
    private function put()
    {
        return $this->setPostFields()
            ->setOption(CURLOPT_CUSTOMREQUEST, 'PUT')
            ->request();
    }

    /**
     * Reset request settings
     * 
     * @return void
     */
    private function reset()
    {
        $this->options = array(
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
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
    public function getData($associative = false)
    {
        if ((false !== $this->response) && is_null($this->data))
        {
            $this->data = json_decode($this->response, $associative);
        }

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
     * @param  string $key info array key
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

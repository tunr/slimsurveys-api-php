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
     * Formatted response data
     *
     * @var array
     */
    private $data = null;

    /**
     * Response info
     *
     * @var array
     */
    private $info = array();

    /**
     * Constructor
     *
     * @param string $key   api key
     * @param string $token auth token
     */
    public function __construct($key, $token = '')
    {
        $this->key   = $key;
        $this->token = $token;
    }

// ===================================================================================
// #auth
// ===================================================================================

    /**
     * Get authorization token
     * 
     * @return Client
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
// #answers
// ===================================================================================

    /**
     * Get authorization token
     * 
     * @return Client
     */
    public function getSurveyAnswers($surveyId, $uvid = null)
    {
        return $this->setUrl('answers/survey/' . $surveyId)
            ->setField('uvid', $uvid)
            ->get();
    }

    /**
     * Get survey answers by uid
     *
     * @param  string $surveyId survey id
     * @param  string $uvid     uvid
     * @return Client
     */
    public function getSurveyAnswersByUid($surveyId, $uvid = null)
    {
        return $this->setUrl('answers/survey')
            ->setQuery('survey_uid', $surveyId)
            ->setField('uvid', $uvid)
            ->get();
    }

    /**
     * Get question answers
     * 
     * @param  integer $questionId question id
     * @return Client
     */
    public function getQuestionAnswers($questionId)
    {
        return $this->setUrl('answers/question/' . $questionId)->get();
    }

    /**
     * Create question answer
     * 
     * @param  integer $questionId question id
     * @param  string  $answer     answer
     * @param  string $milestone   milestone
     * @param  string $uvid        uvid
     * @return Client
     */
    public function createQuestionAnswer($questionId, $answer, $milestone = '', $uvid = '')
    {
        return $this->setUrl('answers/question/' . $questionId)
            ->setField('answer', $answer)
            ->setField('milestone', $milestone)
            ->setField('uvid', $uvid)
            ->post();
    }

// ===================================================================================
// #identities
// ===================================================================================

    // create identity
    public function createSurveyIdentity($surveyId, $uvid, $data)
    {
        return $this->setUrl('identities/create/' . $surveyId)
            ->setField('uvid', $uvid)
            ->setFields($data)
            ->post();
    }

    // create survey identity by uid
    public function createSurveyIdentityByUid($surveyId, $uvid, $data)
    {
        return $this->setUrl('identities/create')
            ->setQuery('survey_uid', $surveyId)
            ->setField('uvid', $uvid)
            ->setFields($data)
            ->post();
    }

// ===================================================================================
// #images
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
// #options
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
// #questions
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
// #results
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
// #surveys
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
// #users
// ===================================================================================

    // Get authenticated users account
    public function me()
    {
        return $this->setUrl('users/me')->get();
    }

    public function register($email, $password, $notifications = true)
    {
        return $this->setUrl('users/signup')
            ->setField('email', $email)
            ->setField('password', $password)
            ->setField('notifications', $notifications)
            ->post();
    }

    public function getPasswordResetToken($email)
    {
        return $this->setUrl('users/forgot')
            ->setField('email', $email)
            ->post();
    }

    public function resetPassword($token, $password, $confirm)
    {
        return $this->setUrl('users/reset/' . $token)
            ->setField('password', $password)
            ->setField('confirm', $confirm)
            ->post();
    }

    public function updateSurveyTab($surveyId)
    {
        return $this->setUrl('users/tab/' . $surveyId)->post();
    }

    public function updateSurveyTabByUid($surveyId)
    {
        return $this->setUrl('users/tab')
            ->setQuery('survey_id', $surveyId)
            ->post();
    }

    public function updateVanityName($name)
    {
        return $this->setUrl('users/vanity')
            ->setField('url', $name)
            ->post();
    }

    public function updatePrivacy($flag)
    {
        return $this->setUrl('users/privacy')
            ->setField('privacy', $flag)
            ->post();
    }

    public function updateEmail($email)
    {
        return $this->setUrl('users/email')
            ->setField('email', $email)
            ->post();
    }

    public function updateThanks($facebook = null, $twitter = null, $website = null)
    {
        return $this->setUrl('users/thanks')
            ->setField('facebook', $facebook)
            ->setField('twitter', $twitter)
            ->setField('website', $website)
            ->post();

    }

    public function updatePassword($password, $confirm)
    {
        return $this->setUrl('users/password')
            ->setField('password', $password)
            ->setField('confirm', $confirm)
            ->post();
    }

    public function deleteSurveyTab()
    {
        return $this->setUrl('users/tab')->delete();
    }

// ===================================================================================
// #request
// ===================================================================================

    /**
     * Set api token
     *
     * @param  string $token api token
     * @return Client
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set url
     *
     * @param  string $url resource url
     * @return Client
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
     * @return Client
     */
    private function buildHeader()
    {
        $this->headers['X-API-KEY'] = (string) $this->key;

        if ($this->token)
        {
            $this->headers['X-AUTH-TOKEN'] = (string) $this->token;
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
     * @param  $key   key
     * @param  $value value
     * @return Client
     */
    private function setHeader($key, $value = null)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set config option
     *
     * @param  $key   key
     * @param  $value value
     * @return Client
     */
    private function setOption($key, $value)
    {
        $this->options[$key] = $value;
        
        return $this;
    }

    /**
     * Set query parameter
     *
     * @param  $key   key
     * @param  $value value
     * @return Client
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
     * @return Client
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
     * @param  $key   key
     * @param  $value value
     * @return Client
     */
    private function setField($key, $value)
    {
        $this->fields[$key] = $value;
        
        return $this;
    }

    /**
     * Set post fields
     *
     * @param  $fields post fields
     * @return Client
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
     * @return Client
     */
    private function get()
    {
        return $this->request();
    }

    /**
     * Post request
     * 
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Get response data
     * 
     * @return object|array
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
     * @return string json
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
     * @return integer
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

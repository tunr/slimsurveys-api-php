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
     * Raw response
     *
     * @var string
     */
    private $response = '';

    /**
     * Response info
     *
     * @var array
     */
    private $info = array();

    /**
     * Formatted response data
     *
     * @var null|object|array
     */
    private $data = null;

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

    /**
     * Create survey identity
     * 
     * @param  integer $surveyId survey id
     * @param  string  $uvid     uvid
     * @param  array   $data     data
     * @return Client
     */
    public function createSurveyIdentity($surveyId, $uvid, $data)
    {
        return $this->setUrl('identities/create/' . $surveyId)
            ->setField('uvid', $uvid)
            ->setFields($data)
            ->post();
    }

    /**
     * Create survey identity by uid
     * 
     * @param  string  $surveyId survey uid
     * @param  string  $uvid     uvid
     * @param  array   $data     data
     * @return Client
     */
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

    /**
     * Get image by id
     * 
     * @param  integer $imageId image id
     * @return Client
     */
    public function getImage($imageId)
    {
        return $this->setUrl('images/image/' . $imageId)->get();
    }

    /**
     * Create question image
     * 
     * @param  integer $questionId question id
     * @param  string  $file       path to file
     * @param  integer $position   position
     * @return Client
     */
    public function createQuestionImage($questionId, $file, $position = 0)
    {
        return $this->setUrl('images/create/' . $questionId)
            ->setField('file', '@' . $file)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Delete image
     * 
     * @param  integer $imageId image id
     * @return Client
     */
    public function deleteImage($imageId)
    {
        return $this->setUrl('images/image/' . $imageId)->delete();
    }

// ===================================================================================
// #options
// ===================================================================================

    /**
     * Get option
     * 
     * @param  integer $optionId option id
     * @return Client
     */
    public function getOption($optionId)
    {
        return $this->setUrl('options/option/' . $optionId)->get();
    }

    /**
     * Create question option
     * 
     * @param  integer $questionId question id
     * @param  string  $value      option text
     * @param  integer $position   position
     * @return Client
     */
    public function createQuestionOption($questionId, $value = null, $position = 0)
    {
        return $this->setUrl('options/create/' . $questionId)
            ->setField('value', $value)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Update option
     * 
     * @param  integer $optionId option id
     * @param  string  $value    option text
     * @param  integer $position position
     * @return Client
     */
    public function updateOption($optionId, $value, $position = 0)
    {
        return $this->setUrl('options/option/' . $optionId)
            ->setField('value', $value)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Delete option
     * 
     * @param  integer $optionId option id
     * @return Client
     */
    public function deleteOption($optionId)
    {
        return $this->setUrl('options/option/' . $optionId)->delete();
    }

// ===================================================================================
// #questions
// ===================================================================================

    /**
     * Get question
     * 
     * @param  integer $questionId question id
     * @return Client
     */
    public function getQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->get();
    }

    /**
     * Create survey question
     * 
     * @param  integer $surveyId survey id
     * @param  string  $type     question type
     * @param  string  $text     question text
     * @param  integer $position position
     * @return Client
     */
    public function createSurveyQuestion($surveyId, $type, $text = '', $position = 0)
    {
        return $this->setUrl('questions/create/' . $surveyId)
            ->setField('type', $type)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Create survey question by uid
     * 
     * @param  integer $surveyId survey uid
     * @param  string  $type     question type
     * @param  string  $text     question text
     * @param  integer $position position
     * @return Client
     */
    public function createSurveyQuestionByUid($surveyId, $type, $text = '', $position = 0)
    {
        return $this->setUrl('questions/create')
            ->setQuery('survey_uid', $surveyId)
            ->setField('type', $type)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Update question
     * 
     * @param  integer $questionId question id
     * @param  string  $text       question text
     * @param  integer $position   position
     * @return Client
     */
    public function updateQuestion($questionId, $text = '', $position = 0)
    {
        return $this->setUrl('questions/question/' . $questionId)
            ->setField('text', $text)
            ->setField('position', $position)
            ->post();
    }

    /**
     * Delete question
     * 
     * @param  integer $questionId question id
     * @return Client
     */
    public function deleteQuestion($questionId)
    {
        return $this->setUrl('questions/question/' . $questionId)->delete();
    }

// ===================================================================================
// #results
// ===================================================================================

    /**
     * Get survey results
     * 
     * @param  integer $surveyId survey id
     * @return Client
     */
    public function getSurveyResults($surveyId)
    {
        return $this->setUrl('results/survey/' . $surveyId)->get();
    }

    /**
     * Get survey results bu uid
     * 
     * @param  integer $surveyId survey uid
     * @return Client
     */
    public function getSurveyResultsByUid($surveyId)
    {
        return $this->setUrl('results/survey')
            ->setQuery('survey_uid', $surveyId)
            ->get();
    }

    /**
     * Get question results
     * 
     * @param  integer $questionId question id
     * @return Client
     */
    public function getQuestionResults($questionId)
    {
        return $this->setUrl('results/question/' . $questionId)->get();
    }

// ===================================================================================
// #surveys
// ===================================================================================

    /**
     * Get survey
     * 
     * @param  integer $surveyId survey id
     * @return Client
     */
    public function getSurvey($surveyId)
    {
        return $this->setUrl('surveys/survey/' . $surveyId)->get();
    }

    /**
     * Get survey by uid
     * 
     * @param  integer $surveyId survey uid
     * @return Client
     */
    public function getSurveyByUid($surveyId)
    {
        return $this->setUrl('surveys/survey')
            ->setQuery('survey_uid', $surveyId)
            ->get();
    }

    /**
     * Get survey embed
     * 
     * @param  integer $surveyId survey id
     * @return Client
     */
    public function getSurveyEmbed($surveyId)
    {
        return $this->setUrl('surveys/embed/' . $surveyId)->get();
    }

    /**
     * Get survey embed by uid
     * 
     * @param  integer $surveyId survey uid
     * @return Client
     */
    public function getSurveyEmbedByUid($surveyId)
    {
        return $this->setUrl('surveys/embed')
            ->setQuery('survey_uid', $surveyId)
            ->get();
    }

    /**
     * Get user surveys
     * 
     * @param  integer $userId user id
     * @return Client
     */
    public function getUserSurveys($userId)
    {
        return $this->setUrl('surveys/user/' . $userId)->get();
    }

    /**
     * Get user surveys by username
     * 
     * @param  string $username username
     * @return Client
     */
    public function getUserSurveysByUsername($username)
    {
        return $this->setUrl('surveys/user')
            ->setQuery('username', $username)
            ->get();
    }

    /**
     * Get authenticated user's surveys
     * 
     * @return Client
     */
    public function getMySurveys()
    {
        return $this->setUrl('surveys/mine')->get();
    }

// ===================================================================================
// #users
// ===================================================================================

    /**
     * Get authenticated user's profile
     * 
     * @return Client
     */
    public function me()
    {
        return $this->setUrl('users/me')->get();
    }

    /**
     * Register new user
     * 
     * @param  string  $email         email
     * @param  string  $password      password
     * @param  boolean $notifications notifications flag
     * @return Client
     */
    public function register($email, $password, $notifications = true)
    {
        return $this->setUrl('users/signup')
            ->setField('email', $email)
            ->setField('password', $password)
            ->setField('notifications', $notifications)
            ->post();
    }

    /**
     * Get password reset token
     * 
     * @param  string $email email
     * @return Client
     */
    public function getPasswordResetToken($email)
    {
        return $this->setUrl('users/forgot')
            ->setField('email', $email)
            ->post();
    }

    /**
     * Reset password
     * 
     * @param  string $token    password reset token
     * @param  string $password password
     * @param  string $confirm  password confirmation
     * @return Client
     */
    public function resetPassword($token, $password, $confirm)
    {
        return $this->setUrl('users/reset/' . $token)
            ->setField('password', $password)
            ->setField('confirm', $confirm)
            ->post();
    }

    /**
     * Update survey tab
     * 
     * @param  integer $surveyId survey id
     * @return Client
     */
    public function updateSurveyTab($surveyId)
    {
        return $this->setUrl('users/tab/' . $surveyId)->post();
    }

    /**
     * Update survey tab by uid
     * 
     * @param  integer $surveyId survey uid
     * @return Client
     */
    public function updateSurveyTabByUid($surveyId)
    {
        return $this->setUrl('users/tab')
            ->setQuery('survey_id', $surveyId)
            ->post();
    }

    /**
     * Update vanity name
     * 
     * @param  string $name vanity name
     * @return Client
     */
    public function updateVanityName($name)
    {
        return $this->setUrl('users/vanity')
            ->setField('url', $name)
            ->post();
    }

    /**
     * Update privacy
     * 
     * @param  boolean $flag true/false flag
     * @return Client
     */
    public function updatePrivacy($flag)
    {
        $flag = (true === $flag) ? 'true' : 'false';

        return $this->setUrl('users/privacy')
            ->setField('privacy', $flag)
            ->post();
    }

    /**
     * Update email address
     * 
     * @param  string $email email
     * @return Client
     */
    public function updateEmail($email)
    {
        return $this->setUrl('users/email')
            ->setField('email', $email)
            ->post();
    }

    /**
     * Update extenal references on thank you page
     * 
     * @param  string $facebook facebook url
     * @param  string $twitter  twitter url
     * @param  string $website  website url
     * @return Client
     */
    public function updateThanks($facebook = null, $twitter = null, $website = null)
    {
        return $this->setUrl('users/thanks')
            ->setField('facebook', $facebook)
            ->setField('twitter', $twitter)
            ->setField('website', $website)
            ->post();

    }

    /**
     * Update password
     * 
     * @param  string $password password
     * @param  string $confirm  password confirmation
     * @return Client
     */
    public function updatePassword($password, $confirm)
    {
        return $this->setUrl('users/password')
            ->setField('password', $password)
            ->setField('confirm', $confirm)
            ->post();
    }

    /**
     * Delete survey tab
     * 
     * @return Client
     */
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

        if (false === $this->response)
        {
            $error     = curl_error($curl);
            $errorCode = curl_errno($curl);

            curl_close($curl);

            throw new ClientException($error, $errorCode);
        }

        curl_close($curl);

        return $this;
    }

    /**
     * Get response data
     *
     * @param  boolean $associative associative array output
     * @return object|array
     */
    public function getData($associative = false)
    {
        if (is_null($this->data))
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

    /**
     * Compare response code
     *
     * @param  integer $code http code
     * @return boolean
     */
    public function isCode($code)
    {
        return (int) $code === $this->getCode();
    }
}

/*
 * SlimSurveys client exception
 */
class ClientException extends \Exception
{
    // generic php exception wrapper
}

/*
 * Verify curl is available
 */
if (!function_exists('curl_init')) {
    throw new \Exception('SlimSurveys API client requires the CURL PHP extension.');
}

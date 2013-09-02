##SlimSurveys API PHP Client

SlimSurveys is a micro-survey platform to help people improve survey completion and abandonment rates with surveys that can be completed in 30 seconds or less.

This repository contains a PHP client library to interface with the SlimSurveys API.

### API Key
First things first, visit the SlimSurveys site to request an API key.

[https://slimsurveys.com/developer](https://slimsurveys.com/developer)

### Installation

Require the SlimSurveys client in your `composer.json` file.

```
"tunr/slimsurveys-api-php": "1.0.*"
```

Then run `composer install` to get the code and update your autoloader.

### Create the Client

Now with your trusty API key, create a client instance.

```
$apiKey = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';

$slimClient = new \SlimSurveys\Client($apiKey);
```

API methods can then be called on the client instance.

```
$response = $slimClient->getMySurveys();
```

### Authentication

Just passing an API key to the client constructor will only get you so far as most API requests require an authentication token. With your SlimSurveys user account email and password you can request an authentication token like so...

```
$email    = 'lamehandle@somewhere.com';
$password = 'XXXXXXXX';

$response = $slimClient->getAuthToken($email, $password);

$data  = $response->getData();
$token = $data->token;

```
Currently an authentication token doesn't expire so it can be stored and used for multiple requests. Once you have a token, it can be passed to the client through the constructor.
```
$apiKey = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$token  = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';

$slimClient = new \SlimSurveys\Client($apiKey, $token);
```

You can also use a token setter to pass a token to an existing client if it was not available when the client was created.

```
$slimClient->setToken($token);
```

### Response
The following methods can be used to inspect the response from an API method.

```
$response->getInfo();
$response->getCode();
$response->isCode($expectedCode);
```

Example:

```
$response = $slimClient->getAuthToken($email, $password);

if ($response->isCode(200))
{
    $data  = $response->getData();
    $token = $data->token;
}
else
{
    // things might not be as awesome as you expected
}
```

### Data

The data getter allows you to return a formatted version of the data return by the SlimSurveys API. Data is returned as a object by default, but can be returned as an associative array by passing `true` to the data getter.

```
$data = $response->getData(true);

$token = $data['token'];
```

### Debugging

Since the entire client instance is returned by each API method, a simple var_dump($response) will provide some visibility into how an API request is configured.

```
$response = $slimClient->getMySurveys();

var_dump($response);
```

### API Methods

For now please review the client code for the available API methods and required parameters.

### Requirements

* PHP 5.3+
* cURL

> Note: A version of PHP < 5.3 will work if class prefixing is used instead of namespacing.

<?php 

namespace Amadeus\Destination;
use GuzzleHttp\Client;

class DestinationClient {
	
	var $client_id;
  	var $client_secret;
  	var $api_token;
  	var $env; 

  	// Describe all API resource in this array
  	var $api_resources = [
	    'pointOfInterest' => [
	      'url_path' => '/v1/reference-data/locations/pois',
	      'required_parameters' => [
	        'latitude',
	        'longitude',
	        'radius'
	      ],
	      'defaults' => [
	      ],
	    ],
	    'locations' => [
	      'url_path' => '/v1/reference-data/locations',
	      'required_parameters' => [
	        'subType',
	        'keyword'
	      ],
	      'parameters' => [
	        'countryCode',
	        'page[limit]',
	        'page[offset]',
	        'sort',
	        'view'
	      ],
	      'defaults' => [
	      ],
	    ],
	    'location' => [
	      'url_path' => '/v1/reference-data/locations/{locationId}',
	      'required_parameters' => [
	      ],
	      'url_parameters' => [
	        'locationId'
	      ],
	      'parameters' => [
	      ],
	      'defaults' => [
	      ],
	    ],
	];



  	public function __construct($client_id, $client_secret, $env = 'DEV'){ 
	    $this->env = $env;
	    $this->client_id    = $client_id; 
	    $this->client_secret= $client_secret; 
	    $this->api_url = $this->env === 'PROD'?"https://api.amadeus.com":"https://test.api.amadeus.com";
	
	    $this->get_client_credentials();
	}

	
	private function api_call($url, $params, $oauth = true){
		
		$client = new Client();
	    
	    if(!$oauth){
	       
	       	$headers = [
	      	'content-type: application/x-www-form-urlencoded', 
	       	];

	    
	      	$response = $client->post( $url, [
			   'headers' => $headers,
			   'form_params' => $params
			] );

	    } else{
	    	if(!$this->api_token){
	        	return false;
	      	}

 			$headers = [
			    'Authorization' => 'Bearer ' . $this->api_token,        
			    'Accept'        => 'application/json',
			];

		    $getdata = http_build_query(
		      $params
		    );

			$response = $client->get( $url . '?' . $getdata , [
			   'headers' => $headers
			] );

	    }


	      if($response->getStatusCode() == 200) {
			    $body = $response->getBody();	    
			}

	      $results = [
		      'http_code' => $response->getStatusCode(),
		      'body'    => $body
	    	];

    		return $results;
  }

  /**
   * Wildcard function to catch and handle all API calls specified in $this->api_resources
   *
   * @param string $resource_name 
   * @param array $args Key 0 being an array of parameters to send with the call
   *
   * @return array An array containing a success key, error messages and the api calls response
   */
  public function __call($resource_name, $args){
    $return_data = [
      'success' => false,
      'msgs'    => [],
      'http_code' => ''
    ];

    if(isset($this->api_resources[$resource_name])){

      if(isset($args[0]) && !empty($args[0])){
        $parameters = $args[0];

        $resource_path = $this->api_resources[$resource_name]['url_path'];

        if(isset($this->api_resources[$resource_name]['url_parameters']) && count($this->api_resources[$resource_name]['url_parameters'])){
          // apply URL parameters to $resource_path
          // all URL parameters are considered mandatory

          for($i = 0; $i < count($this->api_resources[$resource_name]['url_parameters']); $i++){
            $url_param_key = $this->api_resources[$resource_name]['url_parameters'][$i];
            if(array_key_exists($url_param_key, $parameters)){

              $resource_path = str_replace('{' . $url_param_key . '}', urlencode($parameters[$url_param_key]), $resource_path);

              unset( $parameters[$url_param_key] );

            } else {

              $return_data['msgs'][] = 'Required URL Parameter/s Missing. The following mandatory parameter must be provided: "' . $this->api_resources[$resource_name]['url_parameters'][$i] . '".';

              return $return_data;
            }
          }
        }

        $required_parameters = $this->api_resources[$resource_name]['required_parameters'];

        // check if all mandatory parameters are present
        $start_offset = (count($required_parameters) - 1);
        for($i = $start_offset; $i >= 0; $i--){
          if(array_key_exists($required_parameters[$i], $parameters)){
            unset($required_parameters[$i]);
          }
        }
        array_merge($required_parameters);

        if(empty($required_parameters)){
          // good to go
          // let's make the call

          $parameters = array_merge($this->api_resources[$resource_name]['defaults'], $parameters);

          $response = $this->api_call($this->api_url . $resource_path, $parameters);
          $return_data['response_text'] = $response['body'];
          $return_data['http_code'] = $response['http_code'];

          if($return_data['response_text']){
            $return_data['success'] = true;
            $return_data['response'] = json_decode($return_data['response_text'], true);
          }

        } else {
          $return_data['msgs'][] = 'Required Parameter/s Missing. The following mandatory parameter/s must be provided: "' . implode('", "', $required_parameters) . '".';
        }

      } else {
        $return_data['msgs'][] = 'No parameters given. The following mandatory parameter/s must be provided: "' . implode('", "', $this->api_resources[$resource_name]['required_parameters']) . '".';
      }
    } else {
      $return_data['msgs'][] = 'Resource "' . $resource_name . '" not found.';
    }

    return $return_data;
  }


    private function get_client_credentials(){
	    $response_text = $this->api_call($this->api_url . "/v1/security/oauth2/token", [
	      'client_id'     => $this->client_id,
	      'client_secret' => $this->client_secret,
	      'grant_type'    => 'client_credentials',
	    ], false)['body'];
  
	    if($response_text){
	      $response = json_decode($response_text);
	      if(isset($response->state) && $response->state === 'approved'){
	        $this->api_token = $response->access_token;
	      }
	    }
	    return $response;
	}

	public function isOk($bool = true)  
	{  
		return $bool;  
	}


} 
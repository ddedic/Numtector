<?php namespace Ddedic\Numtector\Gateways\Nexmo;

use Ddedic\Numtector\Gateways\GatewayInterface;
use Guzzle\Http\Client;
use Response;

use Ddedic\Numtector\Exceptions\InvalidGatewayResponseException;
use Ddedic\Numtector\Exceptions\InvalidRequestException;
use Ddedic\Numtector\Exceptions\RequiredFieldsException;


class NexmoGateway implements GatewayInterface
{

	protected $api_key;
	protected $api_secret;
	protected $api_endpoint = 'https://rest.nexmo.com';
	protected $remoteClient;
    public static $pricing_url 	= 'account/get-pricing/outbound';


	public function __construct($api_key, $api_secret)
	{

		if($api_key === NULL OR $api_secret === NULL)
			throw new RequiredFieldsException;


		$this->api_key = $api_key;
		$this->api_secret = $api_secret;


		$this->remoteClient = new Client($this->api_endpoint);

	}




	public function getPricing($country)
	{
		return $this->getRemote(self::$pricing_url, array('country' => $country));
	}


	private function getRemote($uri, $params)
	{
        $base_params = array('api_key' => $this->api_key, 'api_secret' => $this->api_secret);
        $parameters = ($params) ? array_merge($base_params, $params) : $base_params;

		$request = $this->remoteClient->get($uri, array(), array(
					    'query' => $parameters
					));

        
       try {
            
            $response = $request->send();    

            

        } catch (\Guzzle\Common\Exception\GuzzleException $e) {
            
            //dd($e->getResponse()->getReasonPhrase());
            $response = array(
                'parameters' => $parameters,
                'code'       => $e->getResponse()->getStatusCode(),
                'message'    => $e->getResponse()->getReasonPhrase()
            );

            //return Response::json($response);
            dd($response);
            //throw new InvalidRequestException;
        }
	


        // Body responsed.
        $body = (string) $response->getBody();

        // Decode json content.
        if ($response->getContentType() == 'application/json' OR ($response->getContentType() == 'application/json;charset=UTF-8'))
        {
            if (function_exists('json_decode') and is_string($body))
            {
                $body = json_decode($body, true);
            }

        } else {

        	throw new InvalidGatewayResponseException;

        }


        return $body;
	}





	private function postRemote()
	{

	}











}
<?php namespace Ddedic\Numtector\Queues;

use Illuminate\Config\Repository;
use Ddedic\Numtector\Countries\CountryInterface;
use Ddedic\Numtector\Operators\OperatorInterface;

use Ddedic\Numtector\Exceptions\GatewayException;
use Config, Queue;


class GatewayPricing {

	protected $countries;
	protected $operators;
	protected $config;
	protected $gateway;
	protected $gatewaysPath = 'Ddedic\Numtector\Gateways\\';


	public function __construct(Repository $config, CountryInterface $countries, OperatorInterface $operators)
	{

		$this->config = $config->get('numtector::config');
		$this->countries = $countries;
		$this->operators = $operators;

 		// Gateway init
 		$gatewayClass = $this->gatewaysPath . $this->config['gateway'];

 		if(!class_exists($gatewayClass)){
 			throw new InvalidGatewayProviderException;
 		}


		$this->gateway = new $gatewayClass ($this->config['gateway.api_key'], $this->config['gateway.api_secret']);


	}


    public function fire($job, $data)
    {

    		$build = array();

			try {

				sleep(0.5);
				$response = $this->gateway->getPricing($data['country']);

				
			}

			catch (GatewayException $e)
			{
				// some error 
				//return;
			}






			if(isset($response))
			{



				if(isset($response['country']))
				{
					if(isset($response['networks']))
					{
						foreach($response['networks'] as $network)
						{

							$networkPrefixInsert = null;

							if (isset($network['ranges']))
							{
								foreach ($network['ranges'] as $prefix)
								{


									$networkPrefixInsert = array(
											'country_code'		=>	$response['country'],
											'network_code'		=>  $network['code'],
											'network_name'		=>	$network['network'],
											'network_prefix'	=>	$prefix

										);

									Queue::push('GatewayPricingFileQueue', $networkPrefixInsert);

								}
							}

							
							//$dbentry = $this->operators->insert($networkPrefixInsert);
							//var_dump($networkPrefixInsert);
							echo "Country: " . $response['country'] . ', network: ' . $network['network'] . ', prefixes:' . count($networkPrefixInsert) .'<br />';


						}
					}

				}

			}


			$job->delete();



        //var_dump("<pre>" . print_r($countries, TRUE) . "</pre>");

        
    }

}
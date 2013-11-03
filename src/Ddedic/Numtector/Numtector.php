<?php namespace Ddedic\Numtector;

use Illuminate\Config\Repository;
use Ddedic\Numtector\Countries\CountryInterface;
use Ddedic\Numtector\Operators\OperatorInterface;

use Ddedic\Numtector\Exceptions\GatewayException;
use Session, Queue;

class Numtector 
{

	protected $countries;
	protected $operators;
	protected $config;

	protected $gatewaysPath = 'Ddedic\Numtector\Gateways\\';
	protected $gateway;

	public function __construct(Repository $config, CountryInterface $countries, OperatorInterface $operators)
	{

		$this->countries = $countries;
		$this->operators = $operators;
		$this->config 	 = $config->get('numtector::config');

		
	}




	public function detectCountries($phoneNumber)
	{
		$phoneNumber = $this->validateDestinationFormat($phoneNumber);
		return $possibleCountries = $this->countries->detectCountriesByPhoneNumber($phoneNumber);

	}


	public function detectOperator($phoneNumber)
	{
		$phoneNumber = $this->validateDestinationFormat($phoneNumber);
		$possibleCountries = $this->detectCountries($phoneNumber);

		return $this->operators->detectOperatorByPhoneNumber($possibleCountries, $phoneNumber);

	}


	public function processNumber($phoneNumber)
	{
		$build = array();
		$phoneNumber = $this->validateDestinationFormat($phoneNumber);

		$possibleCountries = $this->countries->detectCountriesByPhoneNumber($phoneNumber);
		$operater = $this->operators->detectOperatorByPhoneNumber($possibleCountries, $phoneNumber);

		if ($possibleCountries AND $operater)
		{
			$build = array(
					'number'	=>	$phoneNumber,
					'country'	=>	$this->countries->findByIso($operater->country_code)->toArray(),
					'network'	=>	$operater->toArray()
				);

			return $build;
		}

		return false;

	}


	public function refreshOperators()
	{

		if ($this->config['gateway.enabled'] == false)
			die('Gateway disabled!');

		$countries = $this->countries->getAll()->toArray();
		$sessionCountries = array();
		$debug = 0;

		//$this->operators->truncate();

		
		foreach ($countries as $country)
		{

			Queue::push('GatewayPricingQueue', array('country' => $country['iso']));
			$debug++;

			//if($debug == 3) { break; }
		}
		

	}


    private function validateDestinationFormat($inp){
            // Remove any invalid characters
            $ret = preg_replace('/[^0-9]/', '', (string)$inp);

            // Numerical, remove any prepending '00'
            if(substr($ret, 0, 2) == '00'){
                    $ret = substr($ret, 2);
                    $ret = substr($ret, 0, 15);
            }

            // Numerical, remove any prepending '+'
            if(substr($ret, 0, 1) == '+'){
                    $ret = substr($ret, 1);
                    $ret = substr($ret, 0, 15);
            }            
            
            return (string)$ret;
    }


}
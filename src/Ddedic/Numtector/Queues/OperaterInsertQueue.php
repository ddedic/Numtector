<?php namespace Ddedic\Numtector\Queues;

use Illuminate\Config\Repository;
use Ddedic\Numtector\Countries\CountryInterface;
use Ddedic\Numtector\Operators\OperatorInterface;

use Ddedic\Numtector\Exceptions\GatewayException;
use Config, DB, File;

ini_set('memory_limit','1G');

class OperaterInsertQueue {

	protected $operators;

	public function __construct(OperatorInterface $operators)
	{
		$this->operators = $operators;
	}


    public function fire($job, $data)
    {

    	echo 'Inserting operator sql file..' . PHP_EOL;
    	
		DB::transaction(function() use ($data, $job) {

	    	DB::unprepared( $data );
	    	$job->delete();

		});
		
        
    }

}
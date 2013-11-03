<?php namespace Ddedic\Numtector\Queues;

use Illuminate\Config\Repository;
use Ddedic\Numtector\Countries\CountryInterface;
use Ddedic\Numtector\Operators\OperatorInterface;

use Ddedic\Numtector\Exceptions\GatewayException;
use Config, DB, File;


class GatewayPricingFileQueue {

	protected $file;
	protected $template;

	protected $operators;

	public function __construct(OperatorInterface $operators)
	{

		$this->file =  __DIR__.'/../Seeds/Operators.sql';
		$this->template = __DIR__.'/../Seeds/OperatorsSqlTemplate.sql';
		$this->operators = $operators;

	}


    public function fire($job, $data)
    {
		$sql_line = str_replace(
                        array('#table_name#', '#datetime#', '#country#', '#network_code#', '#network_name#', '#prefix#'),
                        array($this->operators->getTableName(), date('Y-m-d H:i:s'), $data['country_code'], str_replace("'", "", stripslashes($data['network_code'])), str_replace("'", "", stripslashes($data['network_name'])), $data['network_prefix']),
                        File::get($this->template)
                    );


		if (file_exists($this->file))
        {
        	File::append($this->file, $sql_line . PHP_EOL);

        } else {

        	File::put($this->file, $sql_line . PHP_EOL);
        }


        $job->delete();

    }
    	

}
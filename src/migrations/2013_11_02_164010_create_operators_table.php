<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateOperatorsTable extends Migration {


	protected $operatorsPath;


	public function __construct()
	{

		$this->operatorsPath = __DIR__ . '/operators';

	}

	public function up()
	{
		Schema::create('operators', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('country_code', 3)->index();
			$table->string('network_code', 20)->index();
			$table->string('network_name', 50);
			$table->string('network_prefix', 15)->unique();
		});


		$this->seedOperators();
	}

	public function down()
	{
		Schema::drop('operators');
	}



	private function seedOperators()
	{

		$sql_files = File::files($this->operatorsPath);
		$index = 0;


		foreach ($sql_files as $sql){

			$current[$index] = File::get($sql);
			Queue::push('OperaterInsertQueue', $current[$index]);
			unset($current[$index]);

			$index++;
		}

	}




}
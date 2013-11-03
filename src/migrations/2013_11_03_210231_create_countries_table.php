<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

	public function up()
	{
		Schema::create('countries', function(Blueprint $table) {
			$table->increments('id');
			$table->string('iso', 2)->unique();
			$table->string('name', 80);
			$table->string('nicename', 80);
			$table->string('iso3', 3);
			$table->smallInteger('numcode');
			$table->integer('phonecode')->index();
			$table->timestamps();
		});


		$countriesDump = File::get( __DIR__ . '/countries/raw_countries.sql');

    	echo 'Inserting countries dump' . PHP_EOL . PHP_EOL;

		DB::transaction(function() use ($countriesDump) {

	    	DB::unprepared( $countriesDump );

		});



	}

	public function down()
	{
		Schema::drop('countries');
	}
}
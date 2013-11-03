<?php namespace Ddedic\Numtector\Countries\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Numtector\Countries\CountryInterface;


class CountryEloquentRepo extends Eloquent implements CountryInterface {

    protected $table = 'countries';

    public $timestamps = false;
    protected $guarded = array();
    protected $hidden = array('id', 'name', 'numcode', 'created_at', 'updated_at');


    public function getAll()
    {
    	return $this->all();
    }

    public function findByIso($iso)
    {
        return $this->where('iso', $iso)->first();
    }


    public function detectCountriesByPhoneNumber($phoneNumber)
    {

    	$found = false;
    	$countries = array();
    	$tempPrefix = substr($phoneNumber, 0, 4); // only first 4 numbers, because no need for other since prefix is max 4 numbers long
    	$foundPrefix = null;

        for( $i = 4; $i >= 1; --$i )
        {
                $country = $this->where('phonecode', substr ($tempPrefix, 0, $i))->first();
                if( $country ) {
                		// only get prefix, later we'll select ALL countries with found prefix (some countries share prefix)
                		$foundPrefix = $country->phonecode;
                        $found = true;
                }
                if( $found ) { break; }
        }

        if ($found)
        {
        	$results = $this->where('phonecode', $foundPrefix)->get();

        	if ($results)
        	{
        		foreach ($results as $c)
        		{
        			$countries[$c->iso] = array('name' => $c->nicename, 'iso_code' => $c->iso ,'phone_prefix' => $c->phonecode);	
        		}
        	}
        }
        

    	return $countries;

    }



}
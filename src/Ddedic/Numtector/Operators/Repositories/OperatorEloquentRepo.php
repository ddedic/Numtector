<?php namespace Ddedic\Numtector\Operators\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Numtector\Operators\OperatorInterface;


class OperatorEloquentRepo extends Eloquent implements OperatorInterface {

    protected $table = 'operators';

    public $timestamps = false;
    protected $guarded = array();
    protected $hidden = array('id', 'created_at', 'updated_at', 'country_code');




    public function getTableName()
    {
        return $this->table;
    }


    public function getAll()
    {
    	return $this->all();
    }




    public function detectOperatorByPhoneNumber($countries, $phoneNumber)
    {

        $found = false;
        $operator = null;

        foreach ($countries as $country)
        {
            for( $i = 10; $i >= 1; --$i )
            {
                    $check = $this->where('country_code', $country['iso_code'])->where('network_prefix', $country['phone_prefix']  . substr ($phoneNumber, strlen($country['phone_prefix']), $i))->first();
                    if( $check ) {
                        $operator = $check;    
                        $found = true;
                    }
                    if( $found ) { break; }
            }
        }


        return $operator;     

    }



}
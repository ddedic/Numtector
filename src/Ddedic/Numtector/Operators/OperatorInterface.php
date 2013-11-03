<?php namespace Ddedic\Numtector\Operators;


interface OperatorInterface {

	public function getTableName();

	public function getAll();

	public function detectOperatorByPhoneNumber($countries, $phoneNumber);

}
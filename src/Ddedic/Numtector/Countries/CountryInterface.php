<?php namespace Ddedic\Numtector\Countries;


interface CountryInterface {

	public function getAll();

	public function detectCountriesByPhoneNumber($phoneNumber);

	public function findByIso($iso);

}
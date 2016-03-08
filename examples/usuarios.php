<?php
class Usuarios {

	// Configuration
	public $id = "usuario";
	public $title = "Usuario";
	public $make = array("create","list","read","edit","remove");
	public $mainColumn = 'nombre';

	// From EasyJsLibrary
	public $columns = array(
		'nombre'			=> 'text',
		'nacimiento'		=> 'date2:es',
		'email'				=> 'email',	
		'contrasenia'		=> 'password'
	);

	public $rules = array(
		'nombre'		=>'required',
		'nacimiento'	=>'required',
		'email'			=>'required',
		'movil'			=>'required',
	);

	public $features = array(
		'user'			=> 	array(
								"username" => 'email',
								'password' => 'contrasenia'
							),
	);

	// See https://laravel.com/docs/4.2/schema
	public function database( $table ) {
		$table->string('nombre');
		$table->date('nacimiento');
		$table->string('email')->unique();
		$table->string('movil');
		return $table;
	}
}
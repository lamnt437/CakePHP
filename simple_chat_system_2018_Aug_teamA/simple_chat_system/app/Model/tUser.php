<?php
App::uses('Model', 'Model');
class tUser extends Model{
	public $useTable = "t_user";

	public $validate = array(
		"name" => array(
			"rule" => "notBlank",
			"message" => "A Name is required!"
		),
		"e-mail" => array(
			"rule" => "notBlank",
			"message" => "E-mail can't be empty!"
		),
		'password' => array(
			'required' => array(
				'rule' => array('minLength', 3),
				'allowEmpty' => false,
				'message' => 'Password must be at least 3 characters'
			)
		)
	);
}
?>

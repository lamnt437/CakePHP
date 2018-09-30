<?php 
App::uses('Model', 'Model');

class tFeed extends Model {
	public $useTable = "t_feed";
	public $validate = array(
		"message" => array(
			"rule" => array('minLength', 1),
			"message" => "Message can't be empty!"
		),
		"Message" => array(
			'img' => array(
				'allowEmpty' => true,
				"rule" => array('checkType'),
				"message" => "Invalid media type!"
			)
		) 
	);

	public function beforeValidate($options = array()) {
		$data =& $this->data[$this->alias];

		if (Hash::get($data, 'Message.img.error') == UPLOAD_ERR_NO_FILE) {
			unset($data['Message']);
		}

		parent::beforeValidate($options);
		return true;
	}

	public function checkType($check_file){
		$valid_types = array(
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
			'video/mp4',
			'video/webm',
			'video/ogg',
			'audio/mpeg',
			'audio/ogg',
			'audio/wav',
			'audio/mp3'
		);
		foreach($valid_types as $valid_type){
			if($check_file['Message']['img']['type'] === $valid_type)
				return true;
		}
		return false;
	}
}
?>

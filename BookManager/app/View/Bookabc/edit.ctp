<h1>Edit Post</h1>
<?php
	echo $this->Form->create('Book');	//generate validating form
	echo $this->Form->input('name', array('label'=>'Ten sach', 
		'required'));
	echo $this->Form->input('detail', array('label'=>'Chi tiet', 'required'));
	echo $this->Form->input('author', array('label'=>'Tac gia', 'required'));

	// echo $this->Form->input('id', array('type' => 'hidden'));
	echo $this->Form->end('Save');
?>
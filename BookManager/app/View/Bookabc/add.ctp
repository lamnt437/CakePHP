<h1>Them sach moi</h1>
<?php
	echo $this->Form->create('Book');
	echo $this->Form->input('name', array('label'=>'Ten sach', 'required'));
	echo $this->Form->input('detail', array('label'=>'Mo ta', 'required'));
	echo $this->Form->input('author', array('label'=>'Tac gia', 'required'));
	echo $this->Form->end('Save');
?>
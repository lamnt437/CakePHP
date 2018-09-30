<h1>Them sach moi</h1>
<?php
	echo $this->Form->create('Book');
	echo $this->Form->input('name', array('label'=>'Ten sach', 'required'));
	echo $this->Form->input('detail', array('label'=>'Mo ta', 'required'));
	echo $this->Form->input('author', array('label'=>'Tac gia', 'required'));
	echo $this->Form->end('Save');
?>

<h1>Danh sach Books:</h1>

<table style="width: 100%">
	<tr>
		<td>ID</td>
		<td>Book</td>
		<td>Detail</td>
		<td>Author</td>
	</tr>
	<?php foreach($books as $book): ?>
		<tr>
			<td><?php echo $book['Book']['id']; ?></td>
			<td><?php echo $book['Book']['name']; ?></td>
			<td><?php echo $book['Book']['detail']; ?></td>
			<td><?php echo $book['Book']['author']; ?></td>
			<td>
	            <?php
	                echo $this->Html->link('Edit', array('controller' => 'bookabc', 'action' => 'edit', $book['Book']['id']));
	            ?>
        	</td>
        	<td>
	            <?php
	                echo $this->Form->postLink('Delete',
		                array('controller' => 'bookabc', 'action' => 'delete', $book['Book']['id']),
		                array('confirm' => 'Are you sure?')
		            );
	            ?>
        	</td>
		</tr>
	<?php endforeach; ?>
</table>
	
	
<?php
	// pr($books_view);
	// echo "string";

	//add link
	echo $this->Html->link(
	    'Them sach moi',
	    array(
	    	'controller' => 'bookabc', //ten controller
	    	'action' => 'add' 
	    )
    );
?>
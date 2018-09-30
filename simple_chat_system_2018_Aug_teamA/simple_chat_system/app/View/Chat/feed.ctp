<?php
$username = $this->Session->read('user.name');
$message_value = "";
$edit_status = "";
if(!empty($edit_data)){
	$message_value = $edit_data['tFeed']['message'];
	$edit_status = $edit_data['tFeed']['id'];
}
?>
<!-- Navigation bar -->
<nav class="navbar navbar-dark bg-dark mb-3">
	<a class="navbar-brand" href="/chat/feed"><strong>Simple Chat System</strong></a>
	<form class="form-inline" action="/user/logout">
		<button type="submit" class="btn btn-outline-danger">Logout</button>
	</form>
</nav>

<!-- Send Message -->
<input id="edit-status" type="hidden" value="<?php echo $edit_status ?>">
<div class="row">
	<div class="col-12">
		<div class="col-sm-11 align-items-end" style="margin: 0 auto">
			<?php echo $this->Form->create('Message', array('type' => 'file')) ?>
			<div class="form-group">
				<label><strong>Name</strong></label>
				<div class="input-group mb-3">
					<?php echo '<div class="form-control" aria-describedby="button-addon2">'.$username.'</div>'?>
					<div class="input-group-append">
						<button class="btn btn-outline-primary" type="submit" id="btn-send" name="send">Send</button>
						<button class="btn btn-outline-success" type="submit" id="btn-save" name="edit">Save</button>
					</div>
					<button class="btn btn-outline-primary" type="button" id="btn-photo" name="photo">Media</button>
					<div class="input-group mb-3" id="choose-img">
						<div class="custom-file">
							<?php 
							echo $this->Form->input('Message.img', array(
								'between' => '<br>',
								'label' => '',
								'type' => 'file',
								'accept' => 'file_extension|audio/*|video/*|image/*|media_type'
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label><strong>Message</strong></label>
				<textarea class="form-control" rows="3" name="message"><?php echo $message_value ?></textarea>
			</div>
		</div>

		<!-- Display Errors -->
		<?php
		if(!empty($errors['message'])){
			echo '<div class="alert alert-danger">';
			echo($errors['message'][0]);
			echo '</div>';
		}
		if(!empty($errors['Message'])){
			echo '<div class="alert alert-danger">';
			echo($errors['Message'][0]);
			echo '</div>';
		}
		?>

		<!-- Display Message -->
		<?php
		echo $this->Form->postLink('Delete',
			array(
				'action' => 'delete', 0
			),
			array(
				'id' => 'hidden-del',
				'class' => 'btn btn-outline-danger',
				'confirm' => 'Are you sure?'
			)
		);
		?>
		<?php foreach($message_views as $message): ?>
			<?php $message_id = $message['tFeed']['id'] ?>
			<?php $media_type = $message['tFeed']['media_type'] ?>
			<div class="display-msg">
				<p class="msg">
					<?php
					echo "<strong>".$message['tFeed']['name']."> "."</strong>";
					echo $message['tFeed']['message']."<br><br>"; 

					if($media_type){
						switch($media_type){
							case 1:
							echo '<img id="img-post" src="/app/webroot/img/upload/'.
							$message['tFeed']['image_file_name'].'"><br><br>';
							break;

							case 2:
							echo '<video controls>';
							echo '<source src="/app/webroot/video/upload/'. $message['tFeed']['image_file_name'] . '" type="' . $message['tFeed']['type'] . '">';
							echo '</video>'."<br><br>";
							break;

							case 3:
							echo '<audio controls>';
							echo '<source src="/app/webroot/audio/upload/'.
							$message['tFeed']['image_file_name'] . '" type="' . $message['tFeed']['type'] . '">';
							echo '</audio>'."<br><br>";
							break; 
						}
					}
					echo "<span class=\"time-display\">".$message['tFeed']['update_at']."</span><br>";
					?>
				</p>
				<div class="msg-option">
					<?php
					if($message['tFeed']['name'] == $username){
						echo $this->Html->link('Edit',
							array(
								'action' => 'feed',
								$message_id
							),
							array(
								'class' => 'btn btn-outline-dark'
							)
						);

						echo $this->Form->postLink('Delete',
							array(
								'action' => 'delete',
								$message_id
							),
							array(
								'class' => 'btn btn-outline-danger',
								'confirm' => 'Are you sure?'
							)
						);
					}
					?>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
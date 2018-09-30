<?php 
App::uses('AppController', 'Controller');

class ChatController extends AppController{

	public $uses = array('tFeed', 'tUser');

	public function feed($id = null){

		//check if user has logged in
		if(!$this->Session->check('user.e-mail')){
			$this->redirect(array('controller' => 'user', 'action' => 'login'));
		}

		$user_email = $this->Session->read('user.e-mail');
		
		//receive edit choice
		if($id){
			$edit_data = $this->tFeed->findById($id);
			if($edit_data){
				$this->Session->write('edit.id', $id);
				$this->Session->write('edit.edited', false);
				$this->set('edit_data', $edit_data);
			}
		}
		
		//send button clicked
		if(isset($this->request->data['send'])){
			$this->tFeed->create();

			//validate request
			$this->tFeed->set($this->request->data);
			if (!$this->tFeed->validates()){
				$errors = $this->tFeed->validationErrors;
				$this->set('errors', $errors);
			}

			//get user_id
			$current_user = $this->tUser->find('first',
				array(
					'conditions' => array(
						'tUser.e-mail' => $user_email
					)
				)
			);
			$user_id = $current_user['tUser']['id'];

			//move uploaded file
			$upload_file = $this->request->data['Message']['img'];
			$filename = $upload_file['name'];
			$this->moveUploadedFile($upload_file);

			//check file type
			$type = $this->request->data['Message']['img']['type'];
			$filetype = $this->checkMedia($upload_file);

			//save user_id, time, filename, filetype to request
			$this->request->data['user_id'] =  $user_id;
			$this->request->data['create_at'] = date("Y-m-d H:i:s");
			$this->request->data['update_at'] = date("Y-m-d H:i:s");
			$this->request->data['image_file_name'] = $filename;
			$this->request->data['media_type'] = $filetype;
			$this->request->data['type'] = $type;

			//save message to database
			if($this->tFeed->save($this->request->data)){
				return $this->redirect(array('action' => 'feed'));
			}
		}

		//edit button clicked
		if(isset($this->request->data['edit'])){
			$edited = $this->Session->read('edit.edited');

			//check if still have message to edit
			if(!$edited){
				//validate request
				$this->tFeed->set($this->request->data);
				if (!$this->tFeed->validates()){
					$errors = $this->tFeed->validationErrors;
					$this->set('errors', $errors);
				}

				//move uploaded file
				$upload_file = $this->request->data['Message']['img'];
				$filename = $upload_file['name'];
				$this->moveUploadedFile($filename);

				//check file type
				$type = $this->request->data['Message']['img']['type'];
				$filetype = $this->checkMedia($upload_file);

				//get message_id to edit
				$id = $this->Session->read('edit.id');

				//get old message
				$this->tFeed->create();	
				$old_data = $this->tFeed->findById($id);

				//edit request before sending
				$update_at = date("Y-m-d H:i:s");
				$this->request->data['update_at'] = $update_at;
				$this->request->data['create_at'] = $old_data['tFeed']['create_at'];
				$this->request->data['image_file_name'] = $filename;
				$this->request->data['media_type'] = $filetype;
				$this->request->data['type'] = $type;

				//save edited message to database	
				$this->tFeed->id = $id;
				if($this->tFeed->save($this->request->data)){
					$this->Session->write('edit.edited', true);
					return $this->redirect(array('action' => 'feed'));
				}
			}
		}

		//get all messages from database
		$message_data = $this->tFeed->find('all',
			array(
				'order' => array(
					'tFeed.create_at' => 'DESC'
				)
			)
		);

		//repack messages to send to view
		$message_views = array();
		if($message_data){
			$index = 0;
			foreach($message_data as $message){
				$user_id = $message['tFeed']['user_id'];
				$user_info = $this->tUser->findById($user_id);
				$username  = $user_info['tUser']['name'];
				$message_views[$index] = array(
					'tFeed' => array(
						'id' => $message['tFeed']['id'],
						'name' => $username,
						'message' => $message['tFeed']['message'],
						'update_at' => $message['tFeed']['update_at'],
						'create_at' => $message['tFeed']['create_at'],
						'image_file_name' => $message['tFeed']['image_file_name'],
						'media_type' => $message['tFeed']['media_type'],
						'type' => $message['tFeed']['type']
					)
				);
				$index++;
			}
		}

		$this->set(compact('message_views', 'errors'));
	}

	public function delete($id){
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}

		if($this->tFeed->delete($id)){
			$this->Flash->success(__("Message has been deleted!"));
		}
		else{
			$this->Flash->error(__("Error while removing message!"));
		}

		return $this->redirect(array('action' => 'feed'));
	}

	public function checkMedia($check_file){
		$valid_img_types = array('image/gif','image/jpeg','image/pjpeg','image/png');
		$valid_vid_types = array('video/mp4','video/webm','video/ogg');
		$valid_aud_types = array('audio/mpeg','audio/ogg','audio/wav', 'audio/mp3');
		if(!empty($check_file['type'])){
			foreach($valid_img_types as $valid_img_type){
				if($check_file['type'] === $valid_img_type)
					return 1;
			}
			foreach($valid_vid_types as $valid_vid_type){
				if($check_file['type'] === $valid_vid_type)
					return 2;
			}
			foreach($valid_aud_types as $valid_aud_type){
				if($check_file['type'] === $valid_aud_type)
					return 3;
			}
		}
		return 0;
	}

	public function moveUploadedFile($file){
		$filetype = $this->checkMedia($file);
		if($filetype === 0)
			return true;

		$dir = WWW_ROOT . 'img/upload';
		switch($filetype){
			case 2:
			$dir = WWW_ROOT . 'video/upload';
			break;

			case 3:
			$dir = WWW_ROOT . 'audio/upload';
			break;
		}

		$filename = $file['name'];
		$target_file = $dir . DS . $filename;
		$tmp_file = $file['tmp_name'];
		if(move_uploaded_file($tmp_file, $target_file)){
			return true;
		}
		return false;
	}
}
<?php
App::uses('AppController', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class UserController extends AppController{

	public $uses = "tUser";

	// Sign-up
	public function regist(){
		if($this->request->is('post')){
			$this->tUser->create();

			// validate
			$regist_data = $this->request->data;
			$this->tUser->set($this->request->data);
			if(!$this->tUser->validates()){
				$regist_errors = $this->tUser->validationErrors;
				$this->set('regist_errors', $regist_errors);
			}

			// check if account already exists
			$check_exist = $this->tUser->find('all', array(
				'conditions' => array(
					'tUser.e-mail' => $regist_data['e-mail']
				)
			));
			if($check_exist){
				$this->Flash->error(__('This E-mail has already been signed up!'));
			}

			//ok
			else{
				// Hashing password before save in database
				$passwordHasher = new SimplePasswordHasher(array('hashType' => 'md5'));
				$this->request->data['password'] = $passwordHasher->hash($regist_data['password']);
				//save
				if($this->tUser->save($this->request->data)){
					$this->Flash->success(__('Successfully signed up!'));
					return $this->redirect(array('action' => 'login'));	
				}
			}
		}
	}
	// Sign-in
	public function login(){
		if ($this->request->is('post')){
			$data = $this->request->data;

			// Hashing password before check with database
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'md5'));
			$data['password'] = $passwordHasher->hash($data['password']);

			//check user account
			$email = $data['e-mail'];
			$password = $data['password'];

			$check_account = $this->tUser->find('first', array(
				'conditions' => array(
					'tUser.e-mail' => $email
				)
			));

			//account exist
			if($check_account){
				$userpass = $check_account['tUser']['password'];

				//account correct
				if($password == $userpass){
					//create session for user

					$this->Session->write('user.e-mail', $email);
					$this->Session->write('user.name', $check_account['tUser']['name']);

					//move to feed
					$this->redirect(array('controller' => 'Chat', 'action' => 'feed'));
				}

				//account incorrect
				else{
					$this->Flash->error(__('Incorrect account!'));
				}
			}

			//account not exist
			else{
				$this->Flash->error(__('Account does not exist!'));
			}
		}
	}

	// Sign-out
	public function logout(){
		$this->Session->destroy();
		$this->redirect(array('action' => 'login'));
	}
}
?>
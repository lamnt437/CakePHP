<?php
	class BookabcController extends AppController{
		public $uses = "Book";	//model name
		public function index(){
			//add
			if ($this->request->is('post')) {
	            $this->Book->create();
	            if ($this->Book->save($this->request->data)) {
	                $this->Flash->success(__('Your post has been saved.'));
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->error(__('Unable to add your post.'));
	        }	
			
			//display
			$books = $this->Book->find('all');	//model
			$this->set('books', $books);	//pass content to view
		}

		// public function add() {
	    //     if ($this->request->is('post')) {
	    //         $this->Book->create();
	    //         if ($this->Book->save($this->request->data)) {
	    //             $this->Flash->success(__('Your post has been saved.'));
	    //             return $this->redirect(array('action' => 'index'));
	    //         }
	    //         $this->Flash->error(__('Unable to add your post.'));
	    //     }
    	// }

    	public function edit($id = null){//change post to book
		    $book = $this->Book->findById($id);
		    if (!$book) {
		        throw new NotFoundException(__('Invalid post'));
		    }

		    if ($this->request->is(array('post', 'put'))) {
		        $this->Book->id = $id;
		        if ($this->Book->save($this->request->data)) {
		            $this->Flash->success(__('Your post has been updated.'));
		            return $this->redirect(array('action' => 'index'));
		        }
		        $this->Flash->error(__('Unable to update your post.'));
		    }

		    if (!$this->request->data) {
		        $this->request->data = $book;
		    }
    	}

    	public function delete($id){	//don't have view, delete in index
    		//check if request is get
    		if($this->request->is('get')){
    			throw new MethodNotAllowedException();
    		}

    		//perform delete
    		if($this->Book->delete($id)){
    			 $this->Flash->success(
		            __('The post with id: %s has been deleted.', h($id))
		        );
    		}
    		else {
		        $this->Flash->error(
		            __('The post with id: %s could not be deleted.', h($id))
		        );
		    }

    		//redirect to index
    		return $this->redirect(array('action' => 'index'));
    	}
	}
?>
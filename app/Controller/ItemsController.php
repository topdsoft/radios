<?php
App::uses('AppController', 'Controller');
/**
 * Items Controller
 *
 * @property Item $Item
 */
class ItemsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Item->recursive = 1;
		$this->set('items', $this->paginate());
		//get list of items this user has viewed
		$hasViewed=$this->Item->ItemsUser->find('all',array('conditions'=>array('user_id'=>$this->Auth->user('id'))));
		$hasViewed2=array();
		foreach($hasViewed as $hv) $hasViewed2[]=$hv['ItemsUser']['item_id'];
		$this->set('hasViewed',$hasViewed2);
		$this->set('admin',($this->Auth->user('id')==1));
//debug($hasViewed2);exit;
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			//returning data
			if(!empty($this->request->data['Comment']['text'])) {
				//user typed something
				$this->request->data['Comment']['item_id']=$id;
				$this->request->data['Comment']['user_id']=$this->Auth->user('id');
				$this->Item->Comment->create();
				$this->Item->Comment->save($this->request->data);
				unset($this->request->data['Comment']);
//debug($this->request->data);exit;
			}
		}
		$this->set('item', $this->Item->read(null, $id));
		$users = $this->Item->User->find('list');
		$this->set(compact('users'));
		$this->set('admin',($this->Auth->user('id')==1));
		//check if user has viewed this item before
		if(!$this->Item->ItemsUser->find('first',array('conditions'=>array('user_id'=>$this->Auth->user('id'),'item_id'=>$id)))) {
			//first time viewing
			$this->Item->ItemsUser->create();
			$this->Item->ItemsUser->save(array('item_id'=>$id,'user_id'=>$this->Auth->user('id')));
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Item->create();
			if ($this->Item->save($this->request->data)) {
				$this->Session->setFlash(__('The item has been created'),'default',array('class'=>'success'));
				$this->redirect(array('action' => 'edit',$this->Item->getInsertId()));
			} else {
				$this->Session->setFlash(__('The item could not be created. Please, try again.'));
			}
		}
		$users = $this->Item->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Item->save($this->request->data)) {
				$this->Session->setFlash(__('The item has been saved'),'default',array('class'=>'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Item->read(null, $id);
		}
		$users = $this->Item->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->Item->delete()) {
			$this->Session->setFlash(__('Item deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Item was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

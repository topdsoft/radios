<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	
	public function beforeFilter() {
		$this->Auth->allow('confirm');
//moved to AppController.php
//		$this->Auth->loginError="The Username/Password You Entered Does Not Match Our Records";
//		$this->Auth->authError="You Must Log In To Access This Location";
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if($this->Auth->user('id')!=1) $this->redirect('/');
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if($this->Auth->user('id')!=1) $this->redirect('/');
		if ($this->request->is('post')) {
			$this->request->data['User']['hash']=md5(uniqid(rand(),true));
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->_sendNewUserMail($this->User->GetInsertId());
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			//get last used message
			$lastUser=$this->User->find('first',array('fields'=>array('User.message'),'order'=>array('User.id DESC')));
			$this->request->data['User']['message']=$lastUser['User']['message'];
		}
		$items = $this->User->Item->find('list');
		$this->set(compact('items'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if($this->Auth->user('id')!=1) $this->redirect('/');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your changes have been saved'),'default',array('class'=>'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Your changes could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

	public function chemail() {
		$this->User->id = $this->Auth->user('id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your changes have been saved'),'default',array('class'=>'success'));
				$this->redirect(array('controller'=>'items','action' => 'index'));
			} else {
				$this->Session->setFlash(__('Your changes could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $this->Auth->user('id'));
		}
	}

	public function chpw() {
		$this->User->id = $this->Auth->user('id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$oldpw=$this->User->field('password','id='.$this->Auth->user('id'));
			if (AuthComponent::password($this->request->data['User']['password'])==$oldpw) {
				if($this->request->data['User']['pw1']!=$this->request->data['User']['pw2']) {
					//passwords do not match
					$this->Session->setFlash(__('The passwords you entered do not match each other'));
				} else {
					$this->request->data['User']['id']=$this->Auth->user('id');
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__('Your changes have been saved'),'default',array('class'=>'success'));
						$this->redirect(array('controller'=>'items','action' => 'index'));
					} else {
						$this->Session->setFlash(__('Your changes could not be saved. Please, try again.'));
					}
				}//endif
			} else {
				$this->Session->setFlash(__('Your old password is not correct. Please, try again.'));
			}//endif
		} else {
//			$this->request->data = $this->User->read(null, $this->Auth->user('id'));
		}
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
	public function confirm($code=null) {
		//confirm user account
		if(!$code) {
			//must have code
			$this->Session->setFlash(__('Invalid Hash'));
			$this->redirect(array('/'));
			return false;
		}//endif no code
		$user=$this->User->find('first',array('conditions'=>array('User.hash'=>$code)));
		if(!$user) {
			//code !ok
			$this->Session->setFlash(__('Invalid Hash'));
			$this->redirect(array('/'));
			return false;
		}//endif code not found
		if ($this->request->is('post') || $this->request->is('put')) {
			//validate pws
			if($this->request->data['User']['pw1']!=$this->request->data['User']['pw2']) {
				//passwords do not match
				$this->Session->setFlash(__('The passwords you entered do not match each other'));
			} else {
				//pws ok
				$this->request->data['User']['password']=$this->request->data['User']['pw1'];
				$this->request->data['User']['hash']=null;
				if ($this->User->save($this->request->data)) {
					//log user in
					if($this->Auth->login($this->request->data['User'])) {
						$this->Session->setFlash(__('Your changes have been saved'),'default',array('class'=>'success'));
						$this->redirect(array('controller'=>'items','action' => 'index'));
					}//endif
				} else {
					$this->Session->setFlash(__('Your changes could not be saved. Please, try again.'));
				}
			}//endif
		} else {
			$this->request->data=$user;
		}
	}

	function _sendNewUserMail($id) {
		//sends email for a new user
		App::uses('CakeEmail', 'Network/Email');
		$user=$this->User->read(null,$id);//debug($id);debug($user);
		if ($user) {
			//found ok
			$mail=new CakeEmail('smtp');
			$mail->to($user['User']['email']);
			$mail->subject('Welcome to Internet Model Railroad Database');
			$mail->replyTo('kurtlakin@topdsoft.com');
			$mail->from(array('kurtlakin@topdsoft.com'=>'Top Drawer Software LLC'));
//			$mail->from(array('kurtlakin@gmail.com'=>'My Site'));
			$mail->template('confirm_message');
			$mail->emailFormat('text');
			$mail->viewVars(array('user'=>$user));
			//mail options
/*			$mail->smtpOptions(array(
			    'port'=>'25',
			    'timeout'=>'30',
			    'host'=>'smtp.emailsrvr.com'
			));//*/
			$x=$mail->send();
//debug($x);exit;
		}
	}
}

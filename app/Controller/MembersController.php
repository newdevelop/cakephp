<?php
class MembersController extends AppController {
/*	public $scaffold; */
	public  $helpers = array('Html', 'Form');
	public $components = array('RequestHandler');

	public function index() {
	/*	$params = array(
				'order' => 'modified desc',
				'limit' => 2
		); */
		$this->paginate = array(
			'order' => array('id' => 'desc'),
			'limit' => 2
		);
		$this->set('members', $this->paginate('Member'));
		$this->set('title_for_layout','会員一覧');
	}
	public function view($id=null) {
		$this->Member->id = $id;
		$this->set('member',$this->Member->read());
	} 

	public function add() {
		if($this->request->is('post')){
		$this->Member->set( $this->request->data );
			if($this->request->data['Member']['ans1']) {
				$this->request->data['Member']['ans1'] = implode( ',', $this->request->data['Member']['ans1']);
			} 
			if($this->request->data['Member']['ans7']) {
				$this->request->data['Member']['ans7'] = implode( ',', $this->request->data['Member']['ans7']);
			}		
			if($this->Member->save($this->request->data)){
				$arrVal = array (
        			'name1' => $this->request->data['Member']['name1'],
        			'name2' => $this->request->data['Member']['name2'],
        			'cname' => $this->request->data['Member']['cname'],
				);
				$email = new CakeEmail( 'jvnet');                        // インスタンス化
    			$email->from( array( 'aff1@jvnet.or.jp' => 'Sender'));  // 送信元
    			$email->to( $this->request->data['Member']['email']);                      // 送信先
    			$email->bcc( 'tanaka@jvnet.or.jp');                      // 送信先
    			$email->subject( '会員登録完了');                      // メールタイトル
    			$email->emailFormat( 'text');                            // フォーマット
    			$email->template( 'sample');                           // テンプレートファイル
    			$email->viewVars($arrVal);             // テンプレートに渡す変数
    			if($email->send()) {
      				$this->Session->setFlash('会員登録完了！');
					$this->redirect(array('action'=>'index'));
				} else {
					$this->Session->setFlash('登録完了メールの送信に失敗しました。');
				}
			} else {
				$this->Session->setFlash('会員登録が、出来ませんでした！');
			}
			if($this->request->data['Member']['ans1']) {
				$this->request->data['Member']['ans1'] = explode( ',', $this->request->data['Member']['ans1']);
			}		
			if($this->request->data['Member']['ans7']) {
				$this->request->data['Member']['ans7'] = explode( ',', $this->request->data['Member']['ans7']);
			}		
			}
	}

	public function edit($id=null) {
		$this->Member->id = $id;
		if($this->request->is('get')){
			$this->request->data = $this->Member->read();
			if($this->request->data['Member']['ans1']) {
				$this->request->data['Member']['ans1'] = explode( ',', $this->request->data['Member']['ans1']);
			}		
			if($this->request->data['Member']['ans7']) {
				$this->request->data['Member']['ans7'] = explode( ',', $this->request->data['Member']['ans7']);
			}		
		} else {
		$this->Member->set( $this->request->data );
			if($this->request->data['Member']['ans1']) {
				$this->request->data['Member']['ans1'] = implode( ',', $this->request->data['Member']['ans1']);
			} 
			if($this->request->data['Member']['ans7']) {
				$this->request->data['Member']['ans7'] = implode( ',', $this->request->data['Member']['ans7']);
			} 
			if($this->Member->save($this->request->data)){
				$this->Session->setFlash('成功');
				$this->redirect(array('action'=>'index'));
				} else{
				$this->Session->setFlash('更新に失敗しました！');

			}
			if($this->request->data['Member']['ans1']) {
				$this->request->data['Member']['ans1'] = explode( ',', $this->request->data['Member']['ans1']);
			}		
			if($this->request->data['Member']['ans7']) {
				$this->request->data['Member']['ans7'] = explode( ',', $this->request->data['Member']['ans7']);
			}		
		}
	} 

	public function create_pdf($id=null) {
		$this->Member->id = $id;
		$this->set('member',$this->Member->read());
	    $this->autoLayout = false;
    	$this->RequestHandler->respondAs('application/pdf');

	} 


	public function delete($id=null) {
		if($this->request->is('get')) {
			throw new MethodNotAllowException();
		} 
/*		if($this->Post->delete($id)) {
			$this->Session->setFlash('削除しました！');
			$this->redirect(array('action'=>'index'));
		} */
		if ($this->request->is('ajax')) {
			if ($this->Member->delete($id)) {
				$this->autoRender =false;
				$this->autoLayout =false;
				$response = array('id' => $id);
				$this->header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	$this->redirect(array('action'=>'index'));

	} 
}
<?php
use visualCaptcha\Captcha;
use visualCaptcha\Session;
App::uses('Component', 'Controller');

App::import('Vendor', 'Captcha', array(
	'file' => 'visual_captcha' . DS . 'src' . DS . 'visualCaptcha' . DS . 'Captcha.php' 
));
App::import('Vendor', 'Session', array(
	'file' => 'visual_captcha' . DS . 'src' . DS . 'visualCaptcha' . DS . 'Session.php' 
));

class VisualCaptchaComponent extends Component {
	public $controller = null;
	public $imageField = null;
	public $audioField = null;

	function initialize(Controller $controller) {
		$this->controller = $controller;

		// if Security component is used
		if (array_key_exists('Security', $this->controller->components)) {
			$this->imageField = $this->controller->Session->read('visualcaptcha.frontendData.imageFieldName');
			$this->audioField = $this->controller->Session->read('visualcaptcha.frontendData.audioFieldName');
			
			if ($this->imageField && $this->audioField) {
				$this->controller->Security->unlockedFields = array(
					$this->imageField,
					$this->audioField
				);
			}
		} 
	}

	function startup(Controller $controller) {
		;
	}

	public function generate($count = 5) {
		$session = new Session();
		$assetsPath = APP . 'Vendor' . DS . 'visual_captcha' . DS . 'src' . DS . 'visualCaptcha' . DS . 'assets';
		$captcha = new Captcha($session, $assetsPath);
		$captcha->generate($count);
		return json_encode($captcha->getFrontEndData());
	}

	public function image($index) {
		$session = new Session();
		$captcha = new Captcha($session);
		return $captcha->streamImage(array(), $index, 0);
	}

	public function audio() {
		$session = new Session();
		$captcha = new Captcha($session);
		return $captcha->streamAudio(array(), 'mp3');
	}
	
	public function check() {
		$reqData = $this->controller->request->data;
		
		$session = new Session();
		$captcha = new Captcha($session);
		
		if (isset($reqData[$this->imageField])) {
			return $captcha->validateImage($reqData[$this->imageField]);
		} elseif (isset($reqData[$this->audioField])) {
			return $captcha->validateAudio($reqData[$this->audioField]);
		}
		
		return false;
	}

}

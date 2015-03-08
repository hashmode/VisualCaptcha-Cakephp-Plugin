# VisualCaptcha-Cakephp-Plugin
Cakephp 2.x Plugin for VisualCaptcha https://github.com/emotionLoop/visualCaptcha


## Installation
1. Clone into `app/Plugin` directory and rename it to `VisualCaptcha`
2. Clone the php package `https://github.com/emotionLoop/visualCaptcha-packagist` into `app/Vendor` directory and rename it to `visual_captcha`
3. Add the component in the controller like `public $components = array('VisualCaptcha.VisualCaptcha');` and load from bootstrap: `CakePlugin::load('VisualCaptcha');`.
4. Js options can be taken from here, `https://github.com/emotionLoop/visualCaptcha-frontend-jquery`. 
E.g. lets say we want to set up the actions in UsersController. In View add this:

```
var WEB_ROOT = '<?php echo $this->webroot; ?>';
$(document).ready(function() {
    $('#captcha').visualCaptcha({
        imgPath: WEB_ROOT + 'visual_captcha/img/',  // visual_captcha - according to Plugin's name
        captcha: {
            numberOfImages: 5,
            url: WEB_ROOT,
            routes: {
            	image : 'users/captcha_image',
            	audio : 'users/captcha_audio',
		       	start : 'users/captcha'
            }
        }
    });
});
```

In users controller we should have these actions

```
	public function captcha() {
		$this->autoRender = false;
		echo $this->VisualCaptcha->generate();
    }

	public function captcha_image($index) {
		$this->autoRender = false;
		return $this->VisualCaptcha->image($index);
    }
	
	public function captcha_audio() {
		$this->autoRender = false;
		echo $this->VisualCaptcha->audio();
    }
```


For checking the validity just use `$this->VisualCaptcha->check()`. It will automatically check the `$this->request->data` for the captcha's key => value pair.

## LICENSE

MIT

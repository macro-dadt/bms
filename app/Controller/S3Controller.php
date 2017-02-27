<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

/**
 * S3Controller
 *
 * @package    app.Controller
 */
class S3Controller extends AppController {

/**
 * Component
 *
 * @var array
 */
	public $components = array();


	public $uses = array('AmazonS3');

	public function index() {
		// アップロード
		$result = $this->AmazonS3->putFile(APP . WEBROOT_DIR . '/img/cake.power.gif', '/img/cake.power.gif');
		var_dump($result);
	}
}

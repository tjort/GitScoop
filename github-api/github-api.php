<?php

namespace GithubApi;

require_once('vendor/autoload.php');

use Guzzle\Http\Client;

class GithubApi {

	public static $url = 'https://api.github.com/';
	public $client = null;

	private $user = null;
	private $pass = null;

	public function __construct() {
		$this->client = new Client('https://api.github.com');
	}

	public function authenticate($user, $pass) {
		$this->user = $user;
		$this->pass = $pass;
	}

	public function get($url) {
		$slug = trim(substr(preg_replace('/[^A-Za-z0-9-]+/', '-', $url), 0, 50), '-');
		$data = array();
	    if (!file_exists('cache/' . $slug . '.json')) {
	    	$request = $this->client->get($url);
			if (!is_null($this->user) && !is_null($this->pass)) $request->setAuth($this->user, $this->pass);

			try {
				$this->response = $request->send();
			} catch (\Exception $e) {
				return array();
			}

			$body = (string)$this->response->getBody();
	    	$data = json_decode($body, true);
	    	if (!empty($data)) file_put_contents('cache/' . $slug . '.json', json_encode($data));
	    } else {
	    	$data = json_decode(file_get_contents('cache/' . $slug . '.json'), true);
	    }
	    return $data;
	}
}
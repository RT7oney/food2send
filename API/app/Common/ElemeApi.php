<?php
namespace App\Common;
use App\Common\CommonFunc;

/**
 * 饿了么平台API封装
 */
class ElemeApi {

	private $domain;
	private $consumer_key;
	private $consumer_secret;
	private $restaurant_id;
	private $restaurant_name;

	function __construct() {
		$this->domain = env("ELEME_URL");
		$this->consumer_key = env("ELEME_CONSUMER_KEY");
		$this->consumer_secret = env("ELEME_CONSUMER_SECRET");
		$this->restaurant_id = env("ELEME_RESTAURANT_ID");
		$this->restaurant_name = env("ELEME_RESTAURANT_NAME");
	}

	function request($url, $params) {
		$com = new CommonFunc;
		$params['timestamp'] = time();
		$params['consumer_key'] = $this->consumer_key;
		$final_url = $this->domain . $url;
		$final_params = $this->genSig($final_url, $params, $this->consumer_secret);
		print_r($final_method);
		echo '<br>';
		print_r($final_url);
		echo '<br>';
		print_r($final_params);
		die;
		$com->httpsRequest($final_url, $final_params);
	}

	private function concatParams($params) {
		ksort($params);
		$pairs = array();
		foreach ($params as $key => $val) {
			array_push($pairs, $key . '=' . urlencode($val));
		}
		return join('&', $pairs);
	}

	private function genSig($pathUrl, $params, $consumerSecret) {
		$params = $this->concatParams($params);
		print_r($params); // 字符串a
		echo '<hr>';
		$str = $pathUrl . '?' . $params . $consumerSecret;
		print_r($str); // 字符串b
		echo '<hr>';
		die;
		return sha1(bin2hex($str)); // 字符串c
	}

}
<?php
namespace App\Common;
use App\Common\CommonFunc;
use DB;

/**
 * 达达平台API封装
 */
class DadaApi {

	private $domain;
	private $app_key;
	private $grant_code;
	private $access_token;

	function __construct() {

		$this->domain = env("DADA_URL");
		$this->app_key = env("DADA_CONSUMER_KEY");
		$this->grant_code = $this->getGrantCode();
		$this->access_token = $this->getAccessToken();
	}

	public function request($url, $params) {
		$com = new CommonFunc;
		$url = $this->domain . $url;
		$params['token'] = $this->access_token;
		$params['timestamp'] = time();
		$params['signature'] = $this->getSignature($params['token'], $params['timestamp']);
		return $com->httpsRequest($url, $params);
	}

	private function getGrantCode() {
		$com = new CommonFunc;
		$url = $this->domain . 'oauth/authorize/?scope=dada_base&app_key=' . $this->app_key;
		$res = json_decode($com->httpsRequest($url), true);
		return $res['result']['grant_code'];
	}

	private function getAccessToken() {
		$com = new CommonFunc;
		$data = DB::table('stuff')->where('name', 'dada_token')->value('content');
		$data = json_decode($data, true);
		if (!$data || $data['expire_time'] < time()) {
			$get_url = $this->domain . 'oauth/access_token/?grant_type=authorization_code&app_key=' . $this->app_key . '&grant_code=' . $this->grant_code;
			$refresh_url = $this->domain . 'oauth/refresh_token/?grant_type=refresh_token&app_key=' . $this->app_key . '&refresh_token=' . isset($data['refresh_token']) ? $data['refresh_token'] : '';
			$url = isset($data['expire_time']) ? $refresh_url : $get_url;
			$res = $com->httpsRequest($url);
			if ($res) {
				$res = json_decode($res, true);
				if ($res['status'] == 'ok') {
					$data['access_token'] = $res['result']['access_token'];
					$data['refresh_token'] = $res['result']['refresh_token'];
					$data['expire_time'] = time() + $res['result']['expires_in'];
					$check = DB::table('stuff')->where('name', 'dada_token')->update(['content' => json_encode($data)]);
					if ($check) {
						$access_token = $data['access_token'];
					} else {
						throw new Exception("插入数据库出错", 1);
					}
				} else {
					$com->log('达达获取access_token接口返回错误:' . json_encode($res));
					throw new Exception("达达获取access_token接口返回错误", 1);
				}
			} else {
				throw new Exception("获取access_token出错", 1);
			}
		} else {
			$access_token = $data['access_token'];
		}
		return $access_token;
	}

	private function getSignature($token, $timestamp) {
		####################会报错######################
		//1.需要对以上信息进行字典排序
		$array = array((string) $timestamp, 'dada', $token);
		// $array = array($timestamp, 'dada', $token);
		sort($array);
		//2.将排序后的三个参数拼接后用md5加密
		$tmpstr = implode('', $array);
		###############################################
		// var_dump($array);die;
		// $tmpstr = $token . $timestamp . 'dada';
		// var_dump(md5($tmpstr));die;
		return md5($tmpstr);
	}

}
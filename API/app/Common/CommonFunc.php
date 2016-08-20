<?php
namespace App\Common;
use Log;

class CommonFunc {

	function httpsRequest($url, $data = null) {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		if ($output) {
			curl_close($curl);
			return $output;
		} else {
			$error = curl_error($curl);
			// print_r($error);die;
			curl_close($curl);
			return false;
		}
	}

	function socketRequest($host, $port, $data, $size = 8192) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $host, $port);
		$in = json_encode($data);
		socket_write($socket, $in, strlen($in));
		$out = '';
		while ($tmp = socket_read($socket, $size)) {
			if (strlen($tmp) == 0) {
				break;
			} else {
				$out .= $tmp;
			}
		}
		socket_close($socket);
		return $out;
	}

	function log($log) {
		$log = '[@' . date('Y-m-d H:i:s') . '#]------' . $log . "\r\n";
		Log::info($log);
	}
}
?>
<?php

namespace App\Http\Controllers\V1;
use App\Common\CommonFunc;
use App\Common\DadaApi;
use App\Common\ElemeApi;
use App\Http\Controllers\Controller;

class TestController extends Controller {

	public function eleme() {
		$eleme = new ElemeApi;
		$data['id'] = '123123';
		$data['phones'] = '188888888';
		$data['address'] = '13w1weqwq3w2asdfaw';
		$eleme->request('order/', $data);
	}
	public function dada() {
		$dada = new DadaApi;
		$url = 'v1_0/addOrder/';
		$data['origin_id'] = uniqid(env("DADA_CONSUMER_KEY") . time());
		$data['city_name'] = '上海';
		$data['city_code'] = '021';
		$data['pay_for_supplier_fee'] = 0;
		$data['fetch_from_receiver_fee'] = 0;
		$data['deliver_fee'] = 0;
		$data['tips'] = 0;
		$data['create_time'] = time();
		$data['info'] = 'RyanTylerTest';
		$data['cargo_type'] = 1;
		$data['cargo_weight'] = 1;
		$data['cargo_price'] = 1;
		$data['cargo_num'] = 1;
		$data['is_prepay'] = 1;
		$data['expected_fetch_time'] = 0;
		$data['expected_finish_time'] = 0;
		$data['supplier_id'] = 0;
		$data['supplier_name'] = 'koyo';
		$data['supplier_address'] = 'koyo的店';
		$data['supplier_phone'] = '18817392521';
		$data['supplier_tel'] = '';
		$data['supplier_lat'] = 0;
		$data['supplier_lng'] = 0;
		$data['invoice_title'] = '';
		$data['receiver_name'] = 'Ryan';
		$data['receiver_address'] = 'Ryan的家';
		$data['receiver_phone'] = '18801912170';
		$data['receiver_tel'] = '';
		$data['receiver_lat'] = 0;
		$data['receiver_lng'] = 0;
		// $data['callback'] = 'http://192.168.1.113/food2send/API/public/v1/dadaback';
		$data['callback'] = 'http://www.baidu.com';
		$res = $dada->request($url, $data);
		print_r($res);die;
	}

	public function dadaback() {
		$com = new CommonFunc;
		if (isset($_POST['order_id'])) {
			$res = $com->socketRequest(env("MAIN_HOST"), env("PORT_DADA_CALLBACK"), $_POST);
			print_r($res);die;
		}
	}

}
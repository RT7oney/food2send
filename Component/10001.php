<?php
/**
 * 达达回调url
 */
use Workerman\Worker;

require_once './Workerman/Autoloader.php';
require_once './common/common.php';

$tcp_worker = new Worker("tcp://0.0.0.0:10001");
// 启动4个进程对外提供服务
$tcp_worker->count = 4;

// 当客户端发来数据时
$tcp_worker->onMessage = function ($connection, $data) {
	print_r($data);
	$data = json_decode($data, true);
	if (isset($data['order_id'])) {
		$msg = array('code' => '100010200', 'msg' => 'ok');
	} else {
		$msg = array('code' => '100010400', 'msg' => 'error');
	}
	$connection->send(json_encode($msg));
	$connection->close();
};
Worker::$stdoutFile = '/alidata/log/food2send/10001-' . date('Ym') . '.log';
// 运行worker
Worker::runAll();

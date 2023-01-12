<?php
function post($url, $data = null, $timeoutS = 1, $isProxy = false)
{
    $curl = curl_init();
    $options = [];
    if ($isProxy) {   //是否设置代理
        $proxy = "127.0.0.1";   //代理IP
        $proxyport = "8001";   //代理端口
        $options[CURLOPT_PROXY] = $proxy . ":" . $proxyport;
    }
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_SSL_VERIFYPEER] = FALSE;
    $options[CURLOPT_SSL_VERIFYHOST] = FALSE;
    if (!empty($data)) {
        $options[CURLOPT_POST] = 1;
        $options[CURLOPT_HTTPHEADER][] = 'cache-control: no-cache';
        if (is_array($data)) {
            $data = json_encode($data);
            $options[CURLOPT_HTTPHEADER][] = 'content-type: application/json';
        }
        $options[CURLOPT_POSTFIELDS] = $data;
    }
    $options[CURLOPT_RETURNTRANSFER] = TRUE;
    if ($timeoutS > 0) { //超时时间秒
        $options[CURLOPT_TIMEOUT] = $timeoutS;
    }

    curl_setopt_array($curl, $options);

    $output = curl_exec($curl);
    $error = curl_errno($curl);
    curl_close($curl);
    if ($error) return error_log($error);
    return $output;
}


$filepath = '/tmp/forward_url';
$url = 'http://ls.stevie.top/records/events';
//$url = 'http://httpbin.org/anything';
$data = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

if (file_exists($filepath)) {
    $url = file_get_contents($filepath);
}

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');

if (!empty($_GET['_$'])) {
    try {
        if (!filter_var($_GET['_$'], FILTER_VALIDATE_URL)) throw new \Exception('地址无效');
        $status = get_headers($_GET['_$'])[0] ?? '';
        if (!strstr($status, '200')) throw new \Exception('地址无效');
        $url = file_put_contents($filepath, $_GET['_$']);
        echo '设置成功';
    } catch (\Exception $e) {
        echo $url;
    }
    exit();
}

echo post($url . '?' . http_build_query($_GET), $data);
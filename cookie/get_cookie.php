<?php
error_reporting(E_ALL & ~ E_NOTICE);
header('Origin: https://facebook.com');
define('API_SECRET', 'da76603d5599ed8f3f461d69dd7d1904');
define('BASE_URL', 'https://api.facebook.com/restserver.php');

function sign_creator(&$data) {
    $sig = "";
    foreach($data as $key => $value){$sig .= "$key=$value";}
    $sig .= API_SECRET;
    $sig = md5($sig);
    return $data['sig'] = $sig;
}

function cURL($method = 'GET', $url = false, $data, $user_agent) {
    $c = curl_init();
    $opts = array(
        CURLOPT_URL => ($url ? $url : BASE_URL).($method == 'GET' ? '?'.http_build_query($data) : ''),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => $user_agent
    );
    if($method == 'POST'){
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = $data;
    }
    curl_setopt_array($c, $opts);
    $d = curl_exec($c);
    curl_close($c);
    return $d;
}

if(isset($_POST['u'], $_POST['p'], $_POST['t'])) {
    $input = $_POST;
    $data = array(
        "api_key" => "3e7c78e35a76a9299309885393b02d97",
        "credentials_type" => "password",
        "email" => $input['u'],
        "format" => "JSON",
        "generate_machine_id" => "1",
        "generate_session_cookies" => "1",
        "locale" => "vi_vn",
        "method" => "auth.login",
        "password" => $input['p'],
        "return_ssl_resources" => "0",
        "v" => "1.0");
    sign_creator($data);

    $login_type = intval($_POST['t']);

    $user_agent = 'Opera/9.80 (Series 60; Opera Mini/6.5.27309/34.1445; U; en) Presto/2.8.119 Version/11.10';
    if ($login_type === 2) {
        $user_agent = 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19';
    }


    $response = cURL('GET', false, $data, $user_agent);
    exit($response);
} else {
    echo ('{"error_code": 422, "message": "Unprocessable Entity"}');
    die();
}
?>
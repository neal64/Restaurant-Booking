<?php
$mobile = "";
$message = "";
    $url = "http://sms.qry.in/smsapi.aspx?username=user&password=pass&sender=SENDER&to=" . $mobile . "&message=" . $messages . "&route=route3";

    //echo $url;
    //exit;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    //curl_setopt($ch, CURLOPT_TIMEOUT, 160);
    curl_setopt($ch, CURLOPT_POST, 0);

    $data = curl_exec($ch);
?>
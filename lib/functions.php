<?php

namespace Lib\KirbyVercel;

class Functions {
  public static function deploy()
  {
    $url = option('f-mahler.kirby-vercel.deployurl');
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);
    $output = curl_exec($handle);
    curl_close($handle);
    kirby()->cache('f-mahler.kirby-vercel')->flush();
    return json_encode($output);
  }

  public static function latest() {
    $token = option('f-mahler.kirby-vercel.token');
    $projectid = option('f-mahler.kirby-vercel.projectid');
    $url = 'https://api.vercel.com/v5/now/deployments?limit=1&projectId=' . $projectid;
    $authorization = "Authorization: Bearer " . $token;
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($handle);
    curl_close($handle);
    return json_encode($output);
  }
}

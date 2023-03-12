<?php

class wb_cpanel_api {
  private   $token;
  private   $username;
  private   $host;
  private   $port;
  private   $ssl;
  private   $result;

  function __construct($host,$port,$ssl,$username,$token){
    $this->host = $host;
    $this->port = $port;
    $this->ssl = $ssl;
    $this->username = $username;
    $this->token = $token;
  }

  private function exec($module, $function, $params){
    $curl = curl_init();
    $url = ($this->ssl == true ? 'https' : 'http').'://'.$this->host.':'.$this->port.'/execute/'.$module.'/'.$function.($params!=='' ? '?':'').$params;
    $header[0] = 'Authorization:cpanel '.$this->username.':'.$this->token;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $url);
    $this->result = curl_exec($curl);
    curl_close($curl);
    return $this;
  }

  public function query($module,$function, $params){
    $this->exec($module,$function,$params);
    return $this;
  }

  public function getResult($result_type = 'ARRAY'){
    $temp_result = json_decode($this->result,true);
    switch($result_type){
    case "ARRAY":
      if(!is_array($temp_result)){
        return array(
          'messages'    =>  str_replace('refresh','#replace{refresh}',$this->result),
          'status'      =>  0,
          'metadata'    =>  array(),
          'data'        =>  array(),
          'error'       =>  1,
          'warnings'    =>  null
        );
      }else{
        return $temp_result;
      }
    break;
    case "JSON":
      if(!is_array($temp_result)){
        return json_encode(array(
          'messages'    =>  str_replace('refresh','#replace{refresh}',$this->result),
          'status'      =>  0,
          'metadata'    =>  array(),
          'data'        =>  array(),
          'error'       =>  1,
          'warnings'    =>  null
        ));
      }else{
        return $this->result;
      }
    break;
    }
  }
}

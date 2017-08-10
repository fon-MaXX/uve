<?php
/**
 * Created by PhpStorm.
 * User: MaXX
 * Date: 7/31/17
 * Time: 15:55
 */
namespace Site\FrontendBundle\Services;

class RecaptchaValidate{
    private $site_key=null;
    private $secret_key=null;
    public function __construct($parameters){
        if(isset($parameters['site_key']))$this->site_key=$parameters['site_key'];
        if(isset($parameters['secret_key']))$this->secret_key=$parameters['secret_key'];
    }
    # get success response from recaptcha and return it to controller
    function captchaverify($recaptcha){
        if(!$recaptcha||!$this->secret_key)return false;
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>$this->secret_key,"response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }
}
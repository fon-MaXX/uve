<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/28/2016
 * Time: 05:14 PM
 */
namespace Site\BackendBundle\Exceptions;


class XmlNotValidException extends \Exception
{
    private $errors;
    public function getErrors(){
        return $this->errors;
    }
    public function setErrors($errors){
        $this->errors=$errors;
        return $this;
    }
}
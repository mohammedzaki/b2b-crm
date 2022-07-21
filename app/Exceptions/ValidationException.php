<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Exceptions;

use Illuminate\Http\Response as ResponseClass;

/**
 * Description of ValidationException
 *
 * @author mohammedzaki
 */
class ValidationException extends \Exception {
    
    public $http_code = ResponseClass::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'The given data failed to pass validation.';
    
    /**
     * (PHP 5 &gt;= 5.1.0, PHP 7)<br/>
     * Construct the exception
     * @link http://php.net/manual/en/exception.construct.php
     * @param string $message [optional] <p>
     * The Exception message to throw.
     * </p>
     * @param int $code [optional] <p>
     * The Exception code.
     * </p>
     * @param Throwable $previous [optional] <p>
     * The previous exception used for the exception chaining.
     * </p>
     */
    public function __construct(string $message = "") {
        parent::__construct($message);
    }
}

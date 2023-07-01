<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 12/12/2018
 * Time: 11:14
 */

require_once dirname(__FILE__).'/../../../SEI.php';


class MdUtlRelAnaliseProdutoBD extends InfraBD{

    public function __construct(InfraIBanco $objInfraIBanco){
        parent::__construct($objInfraIBanco);
    }

}
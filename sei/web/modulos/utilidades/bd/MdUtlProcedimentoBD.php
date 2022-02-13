<?
/**
 *
 * 16/08/2021 - criado por michel.hominus
 *
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlProcedimentoBD extends InfraBD {

    public function __construct(InfraIBanco $objInfraIBanco){
        parent::__construct($objInfraIBanco);
    }

}
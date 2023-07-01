<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jaqueline.mendes
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUsuBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

}

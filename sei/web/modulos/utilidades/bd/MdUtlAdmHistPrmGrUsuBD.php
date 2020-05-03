<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/08/2019 - criado por jaqueline.mendes - Cast Group
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmHistPrmGrUsuBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }
}

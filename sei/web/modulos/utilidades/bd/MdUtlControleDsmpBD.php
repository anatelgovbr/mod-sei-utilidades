<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
 * 28/02/2019 - criado por jaqueline.mendes
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlControleDsmpBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

}

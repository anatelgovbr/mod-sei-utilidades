<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/12/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegParamDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_integ_param';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmIntegParam', 'id_md_utl_adm_integ_param');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmIntegracao', 'id_md_utl_adm_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TpParametro', 'tp_parametro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeCampo', 'nome_campo');

	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Identificador', 'identificador');

    $this->configurarPK('IdMdUtlAdmIntegParam',InfraDTO::$TIPO_PK_NATIVA);

  }
}

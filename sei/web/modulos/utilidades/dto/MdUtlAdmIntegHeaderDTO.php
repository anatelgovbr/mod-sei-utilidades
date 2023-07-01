<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegHeaderDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_integ_header';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmIntegHeader', 'id_md_utl_adm_integ_header');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmIntegracao', 'id_md_utl_adm_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Atributo', 'atributo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Conteudo', 'conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDadoConfidencial', 'sin_dado_confidencial');

    $this->configurarPK('IdMdUtlAdmIntegHeader',InfraDTO::$TIPO_PK_NATIVA);

  }
}

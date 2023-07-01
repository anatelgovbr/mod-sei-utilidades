<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_integracao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmIntegracao', 'id_md_utl_adm_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Funcionalidade', 'funcionalidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoIntegracao', 'tipo_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'MetodoAutenticacao', 'metodo_autenticacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'MetodoRequisicao', 'metodo_requisicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'FormatoResposta', 'formato_resposta');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'VersaoSoap', 'versao_soap');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TokenAutenticacao', 'token_autenticacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'UrlWsdl', 'url_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'OperacaoWsdl', 'operacao_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmIntegracao',InfraDTO::$TIPO_PK_NATIVA);

    #$this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}

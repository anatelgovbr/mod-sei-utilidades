<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRevisaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_revisao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRevisao', 'id_md_utl_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEncaminhamentoRevisao', 'sta_encaminhamento_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAnalise', 'sin_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'InformacoesComplementares', 'informacoes_complementares');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Atual', 'dth_atual');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAssociarFila', 'sin_associar_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEncaminhamentoContestacao', 'sta_encaminhamento_contestacao');

    $this->configurarPK('IdMdUtlRevisao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila', 'id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
  }
}

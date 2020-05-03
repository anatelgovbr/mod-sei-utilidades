<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAnaliseDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_analise';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAnalise', 'id_md_utl_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'InformacoesComplementares', 'informacoes_complementares');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEncaminhamentoAnalise', 'sta_encaminhamento_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Atual', 'dth_atual');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAnalise',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila', 'id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
  }
}

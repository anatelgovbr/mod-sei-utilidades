<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtividadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_atividade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAnalise', 'sin_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TmpExecucaoAtv', 'tmp_execucao_atv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PrzExecucaoAtv', 'prz_execucao_atv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TmpExecucaoRev', 'tmp_execucao_rev');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PrzRevisaoAtv', 'prz_revisao_atv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtvRevAmostragem', 'sin_atv_rev_amostragem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Complexidade', 'complexidade' );

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdAutoComplete');

    $this->configurarPK('IdMdUtlAdmAtividade',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');


  }
}

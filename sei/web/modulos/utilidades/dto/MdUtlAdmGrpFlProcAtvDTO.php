<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFlProcAtvDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_grp_fl_proc_atv';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrpFlProcAtv', 'id_md_utl_adm_grp_fl_proc_atv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrpFilaProc', 'id_md_utl_adm_grp_fila_proc');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'a.id_md_utl_adm_atividade', 'md_utl_adm_atividade a');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'ComplexidadeAtividade', 'a.complexidade', 'md_utl_adm_atividade a');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeAtividade', 'a.nome', 'md_utl_adm_atividade a');


      $this->configurarPK('IdMdUtlAdmGrpFlProcAtv',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atividade a', 'a.id_md_utl_adm_atividade');
    $this->configurarFK('IdMdUtlAdmGrpFilaProc', 'md_utl_adm_grp_fila_proc', 'id_md_utl_adm_grp_fila_proc');
  }
}

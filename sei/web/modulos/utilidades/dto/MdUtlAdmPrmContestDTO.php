<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmContestDTO extends InfraDTO
{
    public function getStrNomeTabela()
    {
        return 'md_utl_adm_prm_contest';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmContest', 'id_md_utl_adm_prm_contest');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'QtdDiasUteisReprovacao', 'qtd_dias_uteis_reprovacao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinReprovacaoAutomatica', 'sin_reprovacao_automatica');

        $this->configurarPK('IdMdUtlAdmPrmContest',InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp', 'id_md_utl_adm_tp_ctrl_desemp');
    }
}
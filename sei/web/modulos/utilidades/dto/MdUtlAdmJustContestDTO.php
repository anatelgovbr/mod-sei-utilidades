<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJustContestDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_utl_adm_just_contest';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJustContest', 'id_md_utl_adm_just_contest');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdMdUtlAdmJustContest',InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp', 'id_md_utl_adm_tp_ctrl_desemp');

        $this->configurarExclusaoLogica('SinAtivo', 'N');
    }
}
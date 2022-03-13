<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsAtivDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_utl_adm_rel_prm_ds_ativ';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prioridade', 'prioridade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds', 'md_utl_adm_prm_ds');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade', 'md_utl_adm_atividade');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeAtividade', 'ativ.nome', 'md_utl_adm_atividade ativ');

        $this->configurarPK('IdMdUtlAdmParamDs',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('IdMdUtlAdmAtividade',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atividade ativ', 'ativ.id_md_utl_adm_atividade');
        $this->configurarFK('IdMdUtlAdmParamDs', 'md_utl_adm_prm_ds pd', 'pd.id_md_utl_adm_prm_ds');

    }
}

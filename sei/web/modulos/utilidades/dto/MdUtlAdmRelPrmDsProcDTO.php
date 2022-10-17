<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsProcDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_utl_adm_rel_prm_ds_proc';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTipoProcesso', 'id_md_utl_adm_prm_gr_proc');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prioridade', 'prioridade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds', 'md_utl_adm_prm_ds');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTipoProcesso', 'id_md_utl_adm_prm_gr_proc', 'md_utl_adm_rel_prm_gr_proc');

        $this->configurarPK('IdMdUtlAdmParamDs',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('IdMdUtlAdmTipoProcesso',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarFK('IdMdUtlAdmTipoProcesso', 'md_utl_adm_rel_prm_gr_proc tp', 'tp.id_tipo_procedimento');
        $this->configurarFK('IdMdUtlAdmParamDs', 'md_utl_adm_prm_ds pd', 'pd.id_md_utl_adm_prm_ds');
    }
}

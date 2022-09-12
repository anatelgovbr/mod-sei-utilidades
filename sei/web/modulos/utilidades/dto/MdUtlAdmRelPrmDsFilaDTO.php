<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsFilaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_utl_adm_rel_prm_ds_fila';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prioridade', 'prioridade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds', 'md_utl_adm_prm_ds');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila', 'md_utl_adm_fila');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeFila', 'fl.nome', 'md_utl_adm_fila fl');

        $this->configurarPK('IdMdUtlAdmParamDs',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('IdMdUtlAdmFila',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila fl', 'fl.id_md_utl_adm_fila');
        $this->configurarFK('IdMdUtlAdmParamDs', 'md_utl_adm_prm_ds pd', 'pd.id_md_utl_adm_prm_ds');
    }
}

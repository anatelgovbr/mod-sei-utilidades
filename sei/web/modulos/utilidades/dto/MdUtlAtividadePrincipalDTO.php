<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAtividadePrincipalDTO extends AtividadeDTO
{

    private $ControleDsmpWhere = null;
    private $HistControleDsmpWhere = null;

    public function __construct(){
        $this->ControleDsmpWhere = InfraDTO::$FILTRO_FK_ON;
        $this->HistControleDsmpWhere = InfraDTO::$FILTRO_FK_ON;
        parent::__construct();
    }

    public function montar()
    {
        parent::montar();

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAdmTpCtrlDesemp',
            'mdutlrelund.id_md_utl_adm_tp_ctrl_desemp',
            'md_utl_adm_rel_tp_ctrl_und mdutlrelund');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlControleDsmp',
            'ctrldsmp.id_md_utl_controle_dsmp',
            'md_utl_controle_dsmp ctrldsmp');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlHistControleDsmp',
            'histctrldsmp.id_md_utl_hist_controle_dsmp',
            'md_utl_hist_controle_dsmp histctrldsmp');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUtlTipoProcedimentoProcedimento',
            'procutl.id_tipo_procedimento',
            'procedimento procutl');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaUtlNivelAcessoLocalProtocolo',
            'sta_nivel_acesso_local',
            'protocolo');


       $this->configurarFK('IdProtocolo', 'procedimento procutl', 'procutl.id_procedimento',  InfraDTO::$TIPO_FK_OBRIGATORIA, InfraDTO::$FILTRO_FK_WHERE);


       $this->configurarFK('IdUnidade', 'md_utl_adm_rel_tp_ctrl_und mdutlrelund', 'mdutlrelund.id_unidade');
       $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp mdutltpctrl', 'mdutltpctrl.id_md_utl_adm_tp_ctrl_desemp');
       $this->configurarFK('IdMdUtlAdmPrmGr', 'md_utl_adm_prm_gr mdutlprmgr', 'mdutlprmgr.id_md_utl_adm_prm_gr');

       $this->configurarFK('IdProtocolo', 'md_utl_controle_dsmp ctrldsmp', 'ctrldsmp.id_procedimento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
       $this->configurarFK('IdUnidade', 'md_utl_controle_dsmp ctrldsmp', 'ctrldsmp.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

       $this->configurarFK('IdProtocolo', 'md_utl_hist_controle_dsmp histctrldsmp', 'histctrldsmp.id_procedimento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
       $this->configurarFK('IdUnidade', 'md_utl_hist_controle_dsmp histctrldsmp', 'histctrldsmp.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

        //$this->configurarFK('IdMdUtlCtrlDsmp', 'md_utl_controle_dsmp ctrldsmp', 'ctrldsmp.id_protocolo');

             /*    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                     'IdUnidade',
                     'cpf.id_unidade',
                     'md_utl_controle_dsmp cpf');

                  $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);*/
    }

    public function getControleDsmpTIPOWhere() {
        return $this->ControleDsmpWhere;
    }

    public function setControleDsmpTIPOWhere($ControleDsmpWhere) {
        $this->ControleDsmpWhere = $ControleDsmpWhere;
    }

    public function getHistControleDsmpTIPOWhere() {
        return $this->HistControleDsmpWhere;
    }

    public function setHistControleDsmpTIPOWhere($HistControleDsmpWhere) {
        $this->HistControleDsmpWhere = $HistControleDsmpWhere;
    }



}
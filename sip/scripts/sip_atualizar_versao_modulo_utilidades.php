<?
/**
 * ANATEL
 *
 * 05/07/2018 - criado por jaqueline.mendes - CAST
 *
 */

require_once dirname(__FILE__).'/../web/Sip.php';

class MdUtlAtualizadorSipRN extends InfraRN {

    private $numSeg = 0;
    private $versaoAtualDesteModulo = '1.1.0';
    private $nomeDesteModulo = 'MÓDULO UTILIDADES';
    private $nomeParametroModulo = 'VERSAO_MODULO_UTILIDADES';
    private $historicoVersoes = array('1.0.0','1.1.0');

    private $nomeGestorControleDesempenho = 'Gestor de Controle de Desempenho';
    private $descricaoGestorControleDesempenho = 'Acesso aos recursos específicos de Gestor de Controle de Desempenho do Módulo Utilidades do SEI.';

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSip::getInstance();
    }

    private function inicializar($strTitulo){
        ini_set('max_execution_time','0');
        ini_set('memory_limit','-1');

        try {
            @ini_set('zlib.output_compression','0');
            @ini_set('implicit_flush', '1');
        }catch(Exception $e){}

        ob_implicit_flush();

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        InfraDebug::getInstance()->setBolEcho(true);
        InfraDebug::getInstance()->limpar();

        $this->numSeg = InfraUtil::verificarTempoProcessamento();

        $this->logar($strTitulo);
    }

    private function logar($strMsg){
        InfraDebug::getInstance()->gravar($strMsg);
        flush();
    }

    private function finalizar($strMsg=null, $bolErro){

        if (!$bolErro) {
            $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
            $this->logar('TEMPO TOTAL DE EXECUÇÃO: '.$this->numSeg.' s');
        }else{
            $strMsg = 'ERRO: '.$strMsg;
        }

        if ($strMsg!=null){
            $this->logar($strMsg);
        }

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        $this->numSeg = 0;
        die;
    }

    protected function atualizarVersaoConectado(){

        try {
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO '.$this->nomeDesteModulo.' NO SIP VERSÃO '.SIP_VERSAO);

            //testando versao do framework
            $numVersaoInfraRequerida = '1.502';
            $versaoInfraFormatada = (int) str_replace('.','', VERSAO_INFRA);
            $versaoInfraReqFormatada = (int) str_replace('.','', $numVersaoInfraRequerida);

            if ($versaoInfraFormatada < $versaoInfraReqFormatada){
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL '.VERSAO_INFRA.', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A '.$numVersaoInfraRequerida.')',true);
            }

            //checando BDs suportados
            if (!(BancoSip::getInstance() instanceof InfraMySql) &&
                !(BancoSip::getInstance() instanceof InfraSqlServer) &&
                !(BancoSip::getInstance() instanceof InfraOracle)) {
                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSip::getInstance()), true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sip_teste'))==0){
                BancoSip::getInstance()->executarSql('CREATE TABLE sip_teste (id '.$objInfraMetaBD->tipoNumero().' null)');
            }

            BancoSip::getInstance()->executarSql('DROP TABLE sip_teste');

            $objInfraParametro = new InfraParametro(BancoSip::getInstance());

            $strVersaoModuloUtl = $objInfraParametro->getValor($this->nomeParametroModulo, false);

            //VERIFICANDO QUAL VERSAO DEVE SER INSTALADA NESTA EXECUCAO
            if (InfraString::isBolVazia($strVersaoModuloUtl)){
                $this->instalarv100();
                $this->instalarv110();
                $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '.$this->versaoAtualDesteModulo.' DO '.$this->nomeDesteModulo.' REALIZADA COM SUCESSO NA BASE DO SIP');
                $this->finalizar('FIM', false);
            } else if ( $strVersaoModuloUtl == '1.0.0' ) {
                $this->instalarv110();
                $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '.$this->versaoAtualDesteModulo.' DO '.$this->nomeDesteModulo.' REALIZADA COM SUCESSO NA BASE DO SIP');
                $this->finalizar('FIM', false);
            } else {
                //se a versão instalada já é a atual, então não instala nada e avisa
				$this->logar('A VERSÃO MAIS ATUAL DO '.$this->nomeDesteModulo.' (v'.$this->versaoAtualDesteModulo.') JÁ ESTÁ INSTALADA.');
                $this->finalizar('FIM', false);
            }

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {
            
			InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando versão.', $e);
        }

    }

    //Contem atualizações da versao 1.0.0
	protected function instalarv100(){

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO '.$this->nomeDesteModulo.' NA BASE DO SIP');

        $numIdSistemaSei = $this->_getIdSistema();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO '. $this->nomeDesteModulo .' NA BASE DO SIP...');

        $this->_cadastrarRetornarIdPerfilUtl($numIdSistemaSei);
        $numIdPerfilSeiAdmin     = $this->_getIdPerfil($numIdSistemaSei);
        $numIdPerfilSeiBasico    = $this->_getIdPerfil($numIdSistemaSei, 'Básico');
        $numIdPerfilSeiGestorUtl = $this->_getIdPerfil($numIdSistemaSei, $this->nomeGestorControleDesempenho);

        $numIdMenuSei            = $this->_getIdMenu($numIdSistemaSei);
        $numIdItemMenuSeiAdmin   = $this->_getIdItemMenu($numIdSistemaSei);

        $arrAuditoria            = array();

        $this->logar('CRIANDO RECURSOS QUE SERÃO CHAMADOS VIA MENU');
        $objMdUtlTpControleListar  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_ctrl_desemp_listar');
        $objMdUtlTpAusenciaListar  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_listar');
        $objMdUtlJornadaListar     = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_jornada_listar');

        $objMdUtlControleDsmpListar = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_controle_dsmp_listar');
        $objMdUtlDistribDsmpListar  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_distrib_usuario_listar');
        $objMdUtlMeusProcDsmpListar = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_meus_processos_dsmp_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Controle de Desempenho em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ctrl_desemp_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ctrl_desemp_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ctrl_desemp_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ctrl_desemp_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ctrl_desemp_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Controle de Desempenho em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ctrl_desemp_alterar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Controle de Desempenho em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_ctrl_desemp_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_usu_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_tp_ctrl_desemp_und_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Parâmetrização de Tipo de Controle em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_usu_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_prm_gr_usu_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_prm_gr_proc_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_prm_gr_proc_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_prm_gr_proc_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_prm_gr_proc_selecionar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Parâmetrização de Tipo de Controle em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_prm_gr_proc_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Parâmetrização de Tipo de Controle em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_usu_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_prm_gr_usu_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_prm_gr_proc_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_prm_gr_proc_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_prm_gr_proc_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_prm_gr_proc_selecionar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Parâmetrização de Tipo de Controle em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_prm_gr_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_prm_gr_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_prm_gr_usu_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Ausência em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_ausencia_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Ausência em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_ausencia_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Fila em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_prm_gr_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_fila_prm_gr_usu_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Fila em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_prm_gr_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_fila_prm_gr_usu_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Fila em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_fila_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_fila_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_fila_prm_gr_usu_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Ajuste de Jornada em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_jornada_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_jornada_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_jornada_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_jornada_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_jornada_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_jornada_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_rel_jornada_usu_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Ajuste de Jornada em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_jornada_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_jornada_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_jornada_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_jornada_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_jornada_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_jornada_usu_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_rel_jornada_usu_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Ajuste de Jornada em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_jornada_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_rel_jornada_usu_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Justificativa em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_just_revisao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_just_revisao_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Justificativa em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_just_revisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_just_revisao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_just_revisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_just_revisao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_just_revisao_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Produto em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_produto_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_produto_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Produto em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_produto_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_produto_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_produto_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_produto_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_produto_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Atividade em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atividade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atividade_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atividade_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atividade_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atividade_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atv_serie_prod_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atv_serie_prod_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_atv_serie_prod_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Atividade em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_atividade_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_atividade_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_atv_serie_prod_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_atv_serie_prod_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_atividade_selecionar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo de Atividade em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo de Atividade em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Fila em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fila_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fila_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fila_proc_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Fila em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Processo em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_proc_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_proc_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fila_proc_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Processo em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fila_proc_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fl_proc_atv_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fila_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_grp_fl_proc_atv_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Processo/Atividade em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fl_proc_atv_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fl_proc_atv_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_grp_fl_proc_atv_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Revisão em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_revisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_tp_revisao_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Revisão em Gestor');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_revisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_revisao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_revisao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_revisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorUtl, 'md_utl_adm_tp_revisao_reativar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Gestor do Utilidades na Justificativa  Prazo');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_just_prazo_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico no Controle de Processo ');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_controle_dsmp_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_controle_dsmp_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_controle_dsmp_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_controle_dsmp_associar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Seleção de Usuário Interno (Utilidades) em Básico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_usuario_selecionar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Triagem');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_triagem_consultar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL Básico para Relacionamentos de Triagem');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_triagem_atv_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Análise');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_analise_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Detalhamento do Processo');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_processo_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Revisão');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Relacionamentos de Revisão');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_triagem_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_triagem_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_analise_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_revisao_analise_consultar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_revis_trg_anls_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_revis_trg_anls_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_revis_trg_anls_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_revis_trg_anls_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_revis_trg_anls_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Distribuição');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_distrib_usuario_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_distrib_usuario_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_distrib_usuario_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_distrib_usuario_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Histórico de Dsmp');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_hist_controle_dsmp_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_hist_controle_dsmp_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_hist_controle_dsmp_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_hist_controle_dsmp_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_prm_gr_usu_consultar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL Básico para Relacionamentos da Análise');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_analise_produto_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_analise_produto_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_analise_produto_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_analise_produto_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_rel_analise_produto_consultar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_fila_prm_gr_usu_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_hist_controle_dsmp_alterar');


        //////////////////////////////////////////////////////// MENUS ///////////////////////////////////////////////////////
        $this->logar('CRIANDO e VINCULANDO ITEM MENU PRINCIPAL DO MÓDULO A PERFIL Administração > Controle de Desempenho');

        $objItemMenuDTOCtrlDesempenho = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdmin, $numIdMenuSei, $numIdItemMenuSeiAdmin, null, 'Controle de Desempenho', 0);

        // Tipo de Ausência
        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Utilidades > Controle de Desempenho > Tipo de Ausência em Administrador');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdmin,
            $numIdMenuSei,
            $objItemMenuDTOCtrlDesempenho->getNumIdItemMenu() ,
            $objMdUtlTpAusenciaListar->getNumIdRecurso(),
            'Tipos de Ausência',
            20);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Desempenho > Tipo de Ausência em Gestor');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiGestorUtl,
            $numIdMenuSei,
            $objItemMenuDTOCtrlDesempenho->getNumIdItemMenu() ,
            $objMdUtlTpAusenciaListar->getNumIdRecurso(),
            'Tipos de Ausência',
            20);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Desempenho > Tipos de Controle de Desempenho em Administrador');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdmin,
            $numIdMenuSei,
            $objItemMenuDTOCtrlDesempenho->getNumIdItemMenu() ,
            $objMdUtlTpControleListar->getNumIdRecurso(),
            'Tipos de Controle de Desempenho',
            10);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração >  Controle de Desempenho > Tipos de Controle de Desempenho em Gestor');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiGestorUtl,
            $numIdMenuSei,
            $objItemMenuDTOCtrlDesempenho->getNumIdItemMenu() ,
            $objMdUtlTpControleListar->getNumIdRecurso(),
            'Tipos de Controle de Desempenho',
            10);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração >  Controle de Desempenho > Ajuste de Jornada em Gestor');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiGestorUtl,
            $numIdMenuSei,
            $objItemMenuDTOCtrlDesempenho->getNumIdItemMenu() ,
            $objMdUtlJornadaListar->getNumIdRecurso(),
            'Ajuste de Jornada',
            30);

        $objItemMenuDTOUtilidadesMain = $this->adicionarItemMenu($numIdSistemaSei
            , $numIdPerfilSeiBasico
            , $numIdMenuSei
            , null
            , null
            , 'Controle de Desempenho'
            , 33);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Controle de Processos ao Básico');

        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiBasico,
            $numIdMenuSei,
            $objItemMenuDTOUtilidadesMain->getNumIdItemMenu(),
            $objMdUtlControleDsmpListar->getNumIdRecurso(),
            'Associar Processos a Filas',
            10);


        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiBasico,
            $numIdMenuSei,
            $objItemMenuDTOUtilidadesMain->getNumIdItemMenu(),
            $objMdUtlDistribDsmpListar->getNumIdRecurso(),
            'Distribuição',
            20);


        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiBasico,
            $numIdMenuSei,
            $objItemMenuDTOUtilidadesMain->getNumIdItemMenu(),
            $objMdUtlMeusProcDsmpListar->getNumIdRecurso(),
            'Meus Processos',
            30);


        $this->logar('CADASTRANDO OS RECURSOS PARA AUDITORIA');

        array_push($arrAuditoria,
            '\'md_utl_adm_tp_ctrl_desemp_cadastrar\'',
            '\'md_utl_adm_tp_ctrl_desemp_alterar\'',
            '\'md_utl_adm_tp_ctrl_desemp_desativar\'',
            '\'md_utl_adm_tp_ctrl_desemp_reativar\'',
            '\'md_utl_adm_tp_ctrl_desemp_excluir\'',
            '\'md_utl_adm_prm_gr_cadastrar\'',
            '\'md_utl_adm_prm_gr_alterar\'',
            '\'md_utl_adm_prm_gr_excluir\'',
            '\'md_utl_adm_tp_ausencia_cadastrar\'',
            '\'md_utl_adm_tp_ausencia_alterar\'',
            '\'md_utl_adm_tp_ausencia_desativar\'',
            '\'md_utl_adm_tp_ausencia_reativar\'',
            '\'md_utl_adm_tp_ausencia_excluir\'',
            '\'md_utl_adm_fila_cadastrar\'',
            '\'md_utl_adm_fila_alterar\'',
            '\'md_utl_adm_fila_desativar\'',
            '\'md_utl_adm_fila_reativar\'',
            '\'md_utl_adm_fila_excluir\'',
            '\'md_utl_adm_jornada_cadastrar\'',
            '\'md_utl_adm_jornada_alterar\'',
            '\'md_utl_adm_jornada_desativar\'',
            '\'md_utl_adm_jornada_reativar\'',
            '\'md_utl_adm_jornada_excluir\'',
            '\'md_utl_adm_tp_just_revisao_cadastrar\'',
            '\'md_utl_adm_tp_just_revisao_alterar\'',
            '\'md_utl_adm_tp_just_revisao_desativar\'',
            '\'md_utl_adm_tp_just_revisao_reativar\'',
            '\'md_utl_adm_tp_just_revisao_excluir\'',
            '\'md_utl_adm_tp_produto_cadastrar\'',
            '\'md_utl_adm_tp_produto_alterar\'',
            '\'md_utl_adm_tp_produto_desativar\'',
            '\'md_utl_adm_tp_produto_reativar\'',
            '\'md_utl_adm_tp_produto_excluir\'',
            '\'md_utl_adm_atividade_cadastrar\'',
            '\'md_utl_adm_atividade_alterar\'',
            '\'md_utl_adm_atividade_desativar\'',
            '\'md_utl_adm_atividade_reativar\'',
            '\'md_utl_adm_atividade_excluir\'',
            '\'md_utl_adm_grp_cadastrar\'',
            '\'md_utl_adm_grp_alterar\'',
            '\'md_utl_adm_grp_desativar\'',
            '\'md_utl_adm_grp_reativar\'',
            '\'md_utl_adm_grp_excluir\'',
            '\'md_utl_adm_grp_fila_cadastrar\'',
            '\'md_utl_adm_grp_fila_alterar\'',
            '\'md_utl_adm_grp_fila_excluir\'',
            '\'md_utl_adm_grp_fila_reativar\'',
            '\'md_utl_adm_grp_fila_desativar\'',
            '\'md_utl_adm_grp_fila_proc_cadastrar\'',
            '\'md_utl_adm_grp_fila_proc_alterar\'',
            '\'md_utl_adm_grp_fila_proc_excluir\'',
            '\'md_utl_adm_grp_fl_proc_atv_cadastrar\'',
            '\'md_utl_adm_grp_fl_proc_atv_alterar\'',
            '\'md_utl_adm_grp_fl_proc_atv_excluir\'',
            '\'md_utl_controle_dsmp_cadastrar\'',
            '\'md_utl_controle_dsmp_excluir\'',
            '\'md_utl_controle_dsmp_associar\'',
            '\'md_utl_adm_just_prazo_cadastrar\'',
            '\'md_utl_adm_just_prazo_alterar\'',
            '\'md_utl_adm_just_prazo_desativar\'',
            '\'md_utl_adm_just_prazo_reativar\'',
            '\'md_utl_adm_just_prazo_excluir\'',
            '\'md_utl_triagem_cadastrar\'',
            '\'md_utl_triagem_alterar\'',
            '\'md_utl_triagem_excluir\'',
            '\'md_utl_triagem_desativar\'',
            '\'md_utl_triagem_reativar\'',
            '\'md_utl_analise_cadastrar\'',
            '\'md_utl_analise_alterar\'',
            '\'md_utl_analise_excluir\'',
            '\'md_utl_analise_desativar\'',
            '\'md_utl_analise_reativar\'',
            '\'md_utl_rel_triagem_atv_cadastrar\'',
            '\'md_utl_rel_triagem_atv_alterar\'',
            '\'md_utl_rel_triagem_atv_excluir\'',
            '\'md_utl_rel_triagem_atv_desativar\'',
            '\'md_utl_rel_triagem_atv_reativar\'',
            '\'md_utl_revisao_cadastrar\'',
            '\'md_utl_revisao_alterar\'',
            '\'md_utl_revisao_desativar\'',
            '\'md_utl_revisao_reativar\'',
            '\'md_utl_revisao_excluir\'',
            '\'md_utl_rel_revis_trg_anls_cadastrar\'',
            '\'md_utl_rel_revis_trg_anls_alterar\'',
            '\'md_utl_rel_revis_trg_anls_excluir\'',
            '\'md_utl_adm_tp_revisao_cadastrar\'',
            '\'md_utl_adm_tp_revisao_alterar\'',
            '\'md_utl_adm_tp_revisao_excluir\'',
            '\'md_utl_adm_tp_revisao_desativar\'',
            '\'md_utl_adm_tp_revisao_reativar\'',
            '\'md_utl_distrib_usuario_cadastrar\'',
            '\'md_utl_distrib_usuario_alterar\'',
            '\'md_utl_distrib_usuario_excluir\'',
            '\'md_utl_hist_controle_dsmp_cadastrar\'',
            '\'md_utl_hist_controle_dsmp_excluir\'',
            '\'md_utl_adm_rel_tp_ctrl_desemp_und_cadastrar\'',
            '\'md_utl_adm_rel_tp_ctrl_desemp_und_excluir\'',
            '\'md_utl_adm_rel_tp_ctrl_desemp_usu_cadastrar\'',
            '\'md_utl_adm_rel_tp_ctrl_desemp_usu_excluir\'',
            '\'md_utl_hist_controle_dsmp_alterar\'',
            '\'md_utl_adm_atv_serie_prod_excluir\'',
            '\'md_utl_adm_atv_serie_prod_cadastrar\'',
            '\'md_utl_adm_prm_gr_usu_cadastrar\'',
            '\'md_utl_adm_prm_gr_usu_alterar\'',
            '\'md_utl_adm_prm_gr_usu_excluir\'',
            '\'md_utl_adm_rel_prm_gr_proc_cadastrar\'',
            '\'md_utl_adm_rel_prm_gr_proc_excluir\'',
            '\'md_utl_adm_fila_prm_gr_usu_cadastrar\'',
            '\'md_utl_adm_fila_prm_gr_usu_excluir\'',
            '\'md_utl_adm_rel_jornada_usu_cadastrar\'',
            '\'md_utl_adm_rel_jornada_usu_excluir\'',
            '\'md_utl_rel_analise_produto_cadastrar\'',
            '\'md_utl_rel_analise_produto_alterar\'',
            '\'md_utl_rel_analise_produto_excluir\'');
        $this->_cadastrarAuditoria($numIdSistemaSei, $arrAuditoria);

        $this->logar('ADICIONANDO PARÂMETRO '.$this->nomeParametroModulo.' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( \'1.0.0\',  \'' . $this->nomeParametroModulo . '\' )');
    }

    protected function instalarv110(){
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO '.$this->nomeDesteModulo.' NA BASE DO SIP');

        $numIdSistemaSei              = $this->_getIdSistema();
        $numIdPerfilSeiBasico         = $this->_getIdPerfil($numIdSistemaSei, 'Básico');
        $numIdPerfilSeiAdmin          = $this->_getIdPerfil($numIdSistemaSei);
        $numIdPerfilSeiGestorUtl      = $this->_getIdPerfil($numIdSistemaSei, $this->nomeGestorControleDesempenho);
        $numIdMenuSei                 = $this->_getIdMenu($numIdSistemaSei);
        $numIdItemMenuSeiCtrlDsmpAdm  = $this->_getIdItemMenuControleDesempenho($numIdSistemaSei);

        $this->logar('CRIANDO RECURSOS QUE SERÃO CHAMADOS VIA MENU');
        $objMdUtlJornadaListar        = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_adm_jornada_listar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Justificativa em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_just_revisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_just_revisao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_just_revisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_just_revisao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_just_revisao_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Revisão em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_revisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_revisao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_revisao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_revisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_revisao_reativar');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Produto em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_produto_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_produto_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_produto_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_produto_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_tp_produto_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo de Atividade em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Fila em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Processo em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_proc_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_proc_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fila_proc_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Grupo/Processo/Atividade em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fl_proc_atv_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fl_proc_atv_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_grp_fl_proc_atv_excluir');

        $this->logar('CRIANDO E VINCULANDO RECURSO A PERFIL - Tipo de Atividade em Administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atividade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atividade_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atividade_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atividade_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atividade_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atv_serie_prod_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atv_serie_prod_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_utl_adm_atv_serie_prod_excluir');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_utl_atividade_triagem_listar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração >  Controle de Desempenho > Ajuste de Jornada em Gestor');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdmin,
            $numIdMenuSei,
            $numIdItemMenuSeiCtrlDsmpAdm,
            $objMdUtlJornadaListar->getNumIdRecurso(),
            'Ajuste de Jornada',
            30);

        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.1.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');
    }

    private function adicionarRecursoPerfil($numIdSistema, $numIdPerfil, $strNome, $strCaminho = null){

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO==null){
            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->setNumIdRecurso(null);
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);
            $objRecursoDTO->setStrDescricao(null);

            if ($strCaminho == null){
                $objRecursoDTO->setStrCaminho('controlador.php?acao='.$strNome);
            }else{
                $objRecursoDTO->setStrCaminho($strCaminho);
            }
            $objRecursoDTO->setStrSinAtivo('S');
            $objRecursoDTO = $objRecursoRN->cadastrar($objRecursoDTO);
        }

        if ($numIdPerfil!=null){
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

            if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO)==0){
                $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
            }
        }

        return $objRecursoDTO;

    }

    private function removerRecursoPerfil($numIdSistema, $strNome, $numIdPerfil){

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO!=null){

            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
            $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
        }
    }

    private function desativarRecurso($numIdSistema, $strNome){

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO!=null){
            $objRecursoRN->desativar(array($objRecursoDTO));
        }
    }

    private function removerRecurso($numIdSistema, $strNome){

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO!=null){
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdMenu();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objItemMenuRN = new ItemMenuRN();
            $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

            foreach($arrObjItemMenuDTO as $objItemMenuDTO){
                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->retTodos();
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

                $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
            }

            $objItemMenuRN->excluir($arrObjItemMenuDTO);

            $objRecursoRN->excluir(array($objRecursoDTO));
        }
    }

    private function renomearRecurso($numIdSistema, $strNomeAtual, $strNomeNovo){
        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->retStrCaminho();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNomeAtual);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO!=null){
            $objRecursoDTO->setStrNome($strNomeNovo);
            $objRecursoDTO->setStrCaminho(str_replace($strNomeAtual,$strNomeNovo,$objRecursoDTO->getStrCaminho()));
            $objRecursoRN->alterar($objRecursoDTO);
        }
    }

    private function adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, $numIdItemMenuPai, $numIdRecurso, $strRotulo, $numSequencia ){

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdMenu($numIdMenu);

        if ($numIdItemMenuPai==null){
            $objItemMenuDTO->setNumIdMenuPai(null);
            $objItemMenuDTO->setNumIdItemMenuPai(null);
        }else{
            $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
            $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
        }

        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
        $objItemMenuDTO->setStrRotulo($strRotulo);

        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO==null){
            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->setNumIdItemMenu(null);
            $objItemMenuDTO->setNumIdMenu($numIdMenu);

            if ($numIdItemMenuPai==null){
                $objItemMenuDTO->setNumIdMenuPai(null);
                $objItemMenuDTO->setNumIdItemMenuPai(null);
            }else{
                $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
                $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
            }

            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
            $objItemMenuDTO->setStrRotulo($strRotulo);
            $objItemMenuDTO->setStrDescricao(null);
            $objItemMenuDTO->setNumSequencia($numSequencia);
            $objItemMenuDTO->setStrSinNovaJanela('N');
            $objItemMenuDTO->setStrSinAtivo('S');

            $objItemMenuDTO = $objItemMenuRN->cadastrar($objItemMenuDTO);
        }

        if ($numIdPerfil!=null && $numIdRecurso!=null){
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

            if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO)==0){
                $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
            }

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdRecurso($numIdRecurso);
            $objRelPerfilItemMenuDTO->setNumIdMenu($numIdMenu);
            $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

            if ($objRelPerfilItemMenuRN->contar($objRelPerfilItemMenuDTO)==0){
                $objRelPerfilItemMenuRN->cadastrar($objRelPerfilItemMenuDTO);
            }
        }

        return $objItemMenuDTO;

    }

    private function removerItemMenu($numIdSistema, $numIdMenu, $numIdItemMenu){

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdMenu();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdMenu($numIdMenu);
        $objItemMenuDTO->setNumIdItemMenu($numIdItemMenu);

        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO!=null) {
            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
            $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

            $objItemMenuRN->excluir(array($objItemMenuDTO));
        }
    }

    private function removerPerfil($numIdSistema, $strNome){

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistema);
        $objPerfilDTO->setStrNome($strNome);

        $objPerfilRN = new PerfilRN();
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO!=null){
            $objPermissaoDTO = new PermissaoDTO();
            $objPermissaoDTO->retNumIdSistema();
            $objPermissaoDTO->retNumIdUsuario();
            $objPermissaoDTO->retNumIdPerfil();
            $objPermissaoDTO->retNumIdUnidade();
            $objPermissaoDTO->setNumIdSistema($numIdSistema);
            $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objPermissaoRN = new PermissaoRN();
            $objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
            $objCoordenadorPerfilDTO->retTodos();
            $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
            $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
            $objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));

            $objPerfilRN->excluir(array($objPerfilDTO));
        }
    }

    private function _cadastrarRetornarIdPerfilUtl($numIdSistemaSei){

        $objPerfilRN = new PerfilRN();
        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($this->nomeGestorControleDesempenho);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            $objPerfilDTOGestorDesempenho = new PerfilDTO();
            $objPerfilDTOGestorDesempenho->retNumIdPerfil();
            $objPerfilDTOGestorDesempenho->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTOGestorDesempenho->setStrNome($this->nomeGestorControleDesempenho);
            $objPerfilDTOGestorDesempenho->setStrDescricao($this->descricaoGestorControleDesempenho);
            $objPerfilDTOGestorDesempenho->setStrSinCoordenado('N');
            $objPerfilDTOGestorDesempenho->setStrSinAtivo('S');

            $objPerfilDTOGestorDesempenho = $objPerfilRN->cadastrar($objPerfilDTOGestorDesempenho);

        }


        return true;
    }

    private function _cadastrarAuditoria($numIdSistemaSei, $arrAuditoria){
        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS');

        //novo grupo de regra de auditoria nova
        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Utilidades');

        $objRegraAuditoriaRN  = new RegraAuditoriaRN();
        $countRgAuditoria     = $objRegraAuditoriaRN->contar($objRegraAuditoriaDTO);
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

        if($countRgAuditoria == 0){
            $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');
            $objRegraAuditoriaDTO2 = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO2->retNumIdRegraAuditoria();
            $objRegraAuditoriaDTO2->setNumIdRegraAuditoria(null);
            $objRegraAuditoriaDTO2->setStrSinAtivo('S');
            $objRegraAuditoriaDTO2->setNumIdSistema($numIdSistemaSei);
            $objRegraAuditoriaDTO2->setArrObjRelRegraAuditoriaRecursoDTO( array() );
            $objRegraAuditoriaDTO2->setStrDescricao('Modulo_Utilidades');

            $objRegraAuditoriaDTO = $objRegraAuditoriaRN->cadastrar($objRegraAuditoriaDTO2);
        }

        $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema='.$numIdSistemaSei.' and nome in (
          '.implode(', ', $arrAuditoria).')'
        );

        //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS
        foreach($rs as $recurso){
            BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values ('.$objRegraAuditoriaDTO->getNumIdRegraAuditoria().', '.$numIdSistemaSei.', '.$recurso['id_recurso'].')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
    }

    private function _getIdSistema($nomeSistema = 'SEI'){
        $objSistemaRN = new SistemaRN();
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla($nomeSistema);

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null){
            $msg = 'Sistema '.$nomeSistema. ' não encontrado';
            throw new InfraException($msg);
        }

        return $objSistemaDTO->getNumIdSistema();
    }

    private function _getIdPerfil($numIdSistema, $nomePerfil= 'Administrador', $textoMsgEx = null){
        $objPerfilRN = new PerfilRN();
        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistema);
        $objPerfilDTO->setStrNome($nomePerfil);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if(is_null($textoMsgEx)){
            $textoMsgEx = $nomePerfil;
        }

        if ($objPerfilDTO == null){
            $msg = 'Perfil '.$textoMsgEx. ' do sistema não encontrado.';
            throw new InfraException($msg);
        }

        $numIdPerfilSei = $objPerfilDTO->getNumIdPerfil();

        return $numIdPerfilSei;
    }

    private function _getIdMenu($numIdSistema, $nomeMenu = 'Principal'){
        $objMenuRN  = new MenuRN();
        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistema);
        $objMenuDTO->setStrNome($nomeMenu);
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null){
            throw new InfraException('Menu do sistema não encontrado.');
        }

        $idMenu = $objMenuDTO->getNumIdMenu();

        return $idMenu;
    }

    private function _getIdItemMenu($numIdSistema, $rotuloItemMenu = 'Administração'){
        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setStrRotulo($rotuloItemMenu);
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null){
            $msg = 'Item de menu '.$rotuloItemMenu.' do sistema no encontrado.';
            throw new InfraException($msg);
        }

        $numIdItemMenuSeiAdm = $objItemMenuDTO->getNumIdItemMenu();

        return $numIdItemMenuSeiAdm;
    }

    private function _getIdItemMenuControleDesempenho($numIdSistema, $isNotPai = false){
        $rotuloItemMenu = 'Controle de Desempenho';
        $objItemMenuRN  = new ItemMenuRN();
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();

        if ($isNotPai) {
            $objItemMenuDTO->setNumIdMenuPai(null);
        }else{
            $objItemMenuDTO->setNumIdMenuPai(null, InfraDTO::$OPER_DIFERENTE);
        }

        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setStrRotulo($rotuloItemMenu);
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null){
            $msg = 'Item de menu '.$rotuloItemMenu.' do sistema no encontrado.';
            throw new InfraException($msg);
        }

        $numIdItemMenuSeiAdm = $objItemMenuDTO->getNumIdItemMenu();

        return $numIdItemMenuSeiAdm;
    }
}

//========================= INICIO SCRIPT EXECUÇAO =============

try {

    session_start();

    SessaoSip::getInstance(false);

    $objVersaoRN = new MdUtlAtualizadorSipRN();
    $objVersaoRN->atualizarVersao();

}catch(Exception $e){
    echo(nl2br(InfraException::inspecionar($e)));
    try{LogSip::getInstance()->gravar(InfraException::inspecionar($e));}catch(Exception $e){}
}

//========================== FIM SCRIPT EXECUÇÂO ====================
?>
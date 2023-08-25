<?
require_once dirname(__FILE__) . '/../web/SEI.php';

class MdUtlAtualizadorSeiRN extends InfraRN
{

	private $numSeg = 0;
    private $versaoAtualDesteModulo = '2.1.0';
    private $nomeDesteModulo = 'MÓDULO UTILIDADES';
    private $nomeParametroModulo = 'VERSAO_MODULO_UTILIDADES';
    private $historicoVersoes = array('1.0.0', '1.1.0', '1.2.0', '1.3.0', '1.4.0', '1.5.0','2.0.0','2.1.0');

    public function __construct()
    {
        parent::__construct();
    }

    protected function getHistoricoVersoes()
    {
        return $this->historicoVersoes;
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function inicializar($strTitulo)
    {
        session_start();
        SessaoSEI::getInstance(false);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        @ini_set('implicit_flush', '1');
        ob_implicit_flush();

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        InfraDebug::getInstance()->setBolEcho(true);
        InfraDebug::getInstance()->limpar();

        $this->numSeg = InfraUtil::verificarTempoProcessamento();

        $this->logar($strTitulo);
    }

    protected function logar($strMsg)
    {
        InfraDebug::getInstance()->gravar($strMsg);
        flush();
    }

    protected function finalizar($strMsg = null, $bolErro = false)
    {
        if (!$bolErro) {
            $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
            $this->logar('TEMPO TOTAL DE EXECUÇÃO: ' . $this->numSeg . ' s');
        } else {
            $strMsg = 'ERRO: ' . $strMsg;
        }

        if ($strMsg != null) {
            $this->logar($strMsg);
        }

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        $this->numSeg = 0;
        die;
    }

    protected function normalizaVersao($versao)
    {
        $ultimoPonto = strrpos($versao, '.');
        if ($ultimoPonto !== false) {
            $versao = substr($versao, 0, $ultimoPonto) . substr($versao, $ultimoPonto + 1);
        }
        return $versao;
    }

	protected function atualizarVersaoConectado()
    {
        
        try {
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO ' . $this->nomeDesteModulo . ' NO SEI VERSÃO ' . SEI_VERSAO);

            //checando BDs suportados
            if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                !(BancoSEI::getInstance() instanceof InfraOracle)) {
                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);
            }

            //testando versao do framework
	        $numVersaoInfraRequerida = '2.0.18';
	        if ($this->normalizaVersao(VERSAO_INFRA) < $this->normalizaVersao($numVersaoInfraRequerida)) {
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sei_teste')) == 0) {
                BancoSEI::getInstance()->executarSql('CREATE TABLE sei_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }

            BancoSEI::getInstance()->executarSql('DROP TABLE sei_teste');

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

            $strVersaoModuloUtilidades = $objInfraParametro->getValor($this->nomeParametroModulo, false);

            switch ($strVersaoModuloUtilidades) {
                case '':
                    $this->instalarv100();
                case '1.0.0':
                    $this->instalarv110();
                case '1.1.0':
                    $this->instalarv120();
                case '1.2.0':
                    $this->instalarv130();
                case '1.3.0':
                    $this->instalarv140();
                case '1.4.0':
                    $this->instalarv150();
                case '1.5.0':
                    $this->instalarv200();
                case '2.0.0':
                    $this->instalarv210();
                    break;

				default:
                    $this->logar('A VERSÃO MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') JÁ ESTÁ INSTALADA.');
                    break;

			}

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            throw new InfraException('Erro instalando/atualizando versão.', $e);
        }
    }

    protected function instalarv100()
    {

		$objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('CRIANDO A TABELA md_utl_adm_tp_ctrl_desemp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_tp_ctrl_desemp (
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_prm_gr ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_tp_ctrl_desemp', 'pk_md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_tp_ctrl_desemp');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_tp_ctrl_desemp', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_tp_ctrl_und');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_tp_ctrl_und (
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_tp_ctrl_und', 'pk_md_utl_adm_rel_tp_ctrl_und', array('id_md_utl_adm_tp_ctrl_desemp', 'id_unidade'));

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_tp_ctrl_usu');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_tp_ctrl_usu (
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_tp_ctrl_usu', 'pk_md_utl_adm_rel_tp_ctrl_usu', array('id_md_utl_adm_tp_ctrl_desemp', 'id_usuario'));

        $this->logar('CRIANDO A TABELA md_utl_adm_prm_gr');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_prm_gr (
				id_md_utl_adm_prm_gr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				carga_padrao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_frequencia ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				percentual_teletrabalho ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sin_retorno_ult_fila ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_prm_gr', 'pk_md_utl_adm_prm_gr', array('id_md_utl_adm_prm_gr'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_prm_gr');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_prm_gr', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_prm_gr_usu');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_prm_gr_usu (
				id_md_utl_adm_prm_gr_usu ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_prm_gr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_tipo_presenca ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				fator_desemp_diferenciado ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sta_tipo_jornada ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				fator_reducao_jornada ' . $objInfraMetaBD->tipoNumero() . ' NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_prm_gr_usu', 'pk_md_utl_adm_prm_gr_usu', array('id_md_utl_adm_prm_gr_usu'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_prm_gr_usu');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_prm_gr_usu', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_prm_gr_proc');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_prm_gr_proc (
				id_md_utl_adm_prm_gr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_prm_gr_proc', 'pk_md_utl_adm_rel_prm_gr_proc', array('id_md_utl_adm_prm_gr', 'id_tipo_procedimento'));

        $this->logar('CRIANDO A TABELA md_utl_adm_fila');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_fila (
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				und_esforco_triagem ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				sin_distribuicao_automatica ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_distribuicao_ult_usuario ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				prazo_tarefa  ' . $objInfraMetaBD->tipoTextoVariavel(3) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_fila', 'pk_md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_fila');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_fila', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_fila_prm_gr_usu');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_fila_prm_gr_usu (
				id_md_utl_adm_fila_prm_gr_usu ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_prm_gr_usu ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sin_analista ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_triador ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_revisor ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				percentual_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_fila_prm_gr_usu', 'pk_md_utl_adm_fila_prm_gr_usu', array('id_md_utl_adm_fila_prm_gr_usu'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_fila_prm_gr_usu');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_fila_prm_gr_usu', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_jornada');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_jornada (
				id_md_utl_adm_jornada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				percentual_ajuste ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				dth_inicio ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				dth_fim ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				sta_tipo_ajuste ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_jornada', 'pk_md_utl_adm_jornada', array('id_md_utl_adm_jornada'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_jornada');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_jornada', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_jornada_usu');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_jornada_usu (
				id_md_utl_adm_jornada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_jornada_usu', 'pk_md_utl_adm_rel_jornada_usu', array('id_md_utl_adm_jornada', 'id_usuario'));

        $this->logar('CRIANDO A TABELA md_utl_adm_tp_ausencia');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_tp_ausencia (
				id_md_utl_adm_tp_ausencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_tp_ausencia', 'pk_md_utl_adm_tp_ausencia', array('id_md_utl_adm_tp_ausencia'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_tp_ausencia');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_tp_ausencia', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_tp_ctrl_desemp',
            array('id_md_utl_adm_prm_gr'), 'md_utl_adm_prm_gr', array('id_md_utl_adm_prm_gr'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_tp_ctrl_und', 'md_utl_adm_rel_tp_ctrl_und',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_tp_ctrl_und', 'md_utl_adm_rel_tp_ctrl_und',
            array('id_unidade'), 'unidade', array('id_unidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_tp_ctrl_usu', 'md_utl_adm_rel_tp_ctrl_usu',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_tp_ctrl_usu', 'md_utl_adm_rel_tp_ctrl_usu',
            array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_prm_gr', 'md_utl_adm_prm_gr',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_prm_gr_usu', 'md_utl_adm_prm_gr_usu',
            array('id_md_utl_adm_prm_gr'), 'md_utl_adm_prm_gr', array('id_md_utl_adm_prm_gr'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_prm_gr_usu', 'md_utl_adm_prm_gr_usu',
            array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_prm_gr_proc', 'md_utl_adm_rel_prm_gr_proc',
            array('id_md_utl_adm_prm_gr'), 'md_utl_adm_prm_gr', array('id_md_utl_adm_prm_gr'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_prm_gr_proc', 'md_utl_adm_rel_prm_gr_proc',
            array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_fila', 'md_utl_adm_fila',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_fila_prm_gr_usu', 'md_utl_adm_fila_prm_gr_usu',
            array('id_md_utl_adm_prm_gr_usu'), 'md_utl_adm_prm_gr_usu', array('id_md_utl_adm_prm_gr_usu'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_jornada', 'md_utl_adm_jornada',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_jornada_usu', 'md_utl_adm_rel_jornada_usu',
            array('id_md_utl_adm_jornada'), 'md_utl_adm_jornada', array('id_md_utl_adm_jornada'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_jornada_usu', 'md_utl_adm_rel_jornada_usu',
            array('id_usuario'), 'usuario', array('id_usuario'));


        $this->logar('CRIANDO A TABELA md_utl_adm_tp_just_revisao');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_tp_just_revisao (
                id_md_utl_adm_tp_just_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_tp_just_revisao', 'pk_md_utl_adm_tp_just_revisao', array('id_md_utl_adm_tp_just_revisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_tp_just_revisao', 'md_utl_adm_tp_just_revisao',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));


        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_tp_just_revisao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_tp_just_revisao', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_tp_produto');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_tp_produto (
                id_md_utl_adm_tp_produto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_tp_produto', 'pk_md_utl_adm_tp_produto', array('id_md_utl_adm_tp_produto'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_tp_produto', 'md_utl_adm_tp_produto',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_tp_produto');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_tp_produto', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_tp_revisao');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_tp_revisao (
                id_md_utl_adm_tp_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_justificativa ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_tp_revisao', 'pk_md_utl_adm_tp_revisao', array('id_md_utl_adm_tp_revisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_tp_revisao', 'md_utl_adm_tp_revisao',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_tp_revisao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_tp_revisao', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_atividade');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_atividade (
                id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_analise ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				und_esforco_atv ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				prz_execucao_atv ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				und_esforco_rev ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				prz_revisao_atv ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sin_atv_rev_amostragem ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_atividade', 'pk_md_utl_adm_atividade', array('id_md_utl_adm_atividade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_atividade', 'md_utl_adm_atividade',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_atividade');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_atividade', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_atv_serie_prod');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_atv_serie_prod (
                id_md_utl_adm_atv_serie_prod ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_produto ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_serie ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sta_tipo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_obrigatorio ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sta_aplicabilidade_serie ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL,
				und_esforco_rev_produto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_atv_serie_prod', 'pk_md_utl_adm_atv_serie_prod', array('id_md_utl_adm_atv_serie_prod'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_atv_serie_prod', 'md_utl_adm_atv_serie_prod',
            array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_atv_serie_prod', 'md_utl_adm_atv_serie_prod',
            array('id_md_utl_adm_tp_produto'), 'md_utl_adm_tp_produto', array('id_md_utl_adm_tp_produto'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_adm_atv_serie_prod', 'md_utl_adm_atv_serie_prod',
            array('id_serie'), 'serie', array('id_serie'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_atv_serie_prod');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_atv_serie_prod', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_grp');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_grp (
				id_md_utl_adm_grp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_grp', 'pk_md_utl_adm_grp', array('id_md_utl_adm_grp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_grp');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_grp', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_grp_fila');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_grp_fila (
				id_md_utl_adm_grp_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_grp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_grp_fila', 'pk_md_utl_adm_grp_fila', array('id_md_utl_adm_grp_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_grp_fila');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_grp_fila', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_grp_fila_proc');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_grp_fila_proc (
				id_md_utl_adm_grp_fila_proc ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_grp_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_grp_fila_proc', 'pk_md_utl_adm_grp_fila_proc', array('id_md_utl_adm_grp_fila_proc'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_grp_fila_proc');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_grp_fila_proc', 1);

        $this->logar('CRIANDO A TABELA md_utl_adm_grp_fl_proc_atv');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_grp_fl_proc_atv (
				id_md_utl_adm_grp_fl_proc_atv ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_grp_fila_proc ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_grp_fl_proc_atv', 'pk_md_utl_adm_grp_fl_proc_atv', array('id_md_utl_adm_grp_fl_proc_atv'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_grp_fl_proc_atv');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_grp_fl_proc_atv', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_grp', 'md_utl_adm_grp',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_grp_fila', 'md_utl_adm_grp_fila',
            array('id_md_utl_adm_grp'), 'md_utl_adm_grp', array('id_md_utl_adm_grp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_grp_fila', 'md_utl_adm_grp_fila',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_grp_fila_proc', 'md_utl_adm_grp_fila_proc',
            array('id_md_utl_adm_grp_fila'), 'md_utl_adm_grp_fila', array('id_md_utl_adm_grp_fila'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_grp_fila_proc', 'md_utl_adm_grp_fila_proc',
            array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_grp_fl_proc_atv', 'md_utl_adm_grp_fl_proc_atv',
            array('id_md_utl_adm_grp_fila_proc'), 'md_utl_adm_grp_fila_proc', array('id_md_utl_adm_grp_fila_proc'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_grp_fl_proc_atv', 'md_utl_adm_grp_fl_proc_atv',
            array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));

        //Justificativa de prazo
        $this->logar('CRIANDO A TABELA md_utl_adm_just_prazo');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_just_prazo (
                id_md_utl_adm_just_prazo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_just_prazo', 'pk_md_utl_adm_just_prazo', array('id_md_utl_adm_just_prazo'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_just_prazo', 'md_utl_adm_just_prazo',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_just_prazo');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_just_prazo', 1);

        $this->logar('CRIANDO A TABELA md_utl_triagem');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_triagem (
				id_md_utl_triagem ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				dth_prazo_resposta ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
				informacao_complementar ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				sin_possui_analise ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sta_encaminhamento_triagem ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_triagem', 'pk_md_utl_triagem', array('id_md_utl_triagem'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_triagem', 'md_utl_triagem',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_triagem');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_triagem', 1);

        $this->logar('CRIANDO A TABELA md_utl_rel_triagem_atv');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_rel_triagem_atv (
				id_md_utl_rel_triagem_atv ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_triagem ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				unidade_esforco ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_rel_triagem_atv', 'pk_md_utl_rel_triagem_atv', array('id_md_utl_rel_triagem_atv'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_rel_triagem_atv');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_rel_triagem_atv', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_rel_triagem_atv', 'md_utl_rel_triagem_atv',
            array('id_md_utl_triagem'), 'md_utl_triagem', array('id_md_utl_triagem'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_rel_triagem_atv', 'md_utl_rel_triagem_atv',
            array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));

        //Iniciando tabela de Análise
        $this->logar('CRIANDO A TABELA md_utl_analise');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_analise (
				id_md_utl_analise ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				informacoes_complementares ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				sta_encaminhamento_analise ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_analise', 'pk_md_utl_analise', array('id_md_utl_analise'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_analise', 'md_utl_analise',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_analise');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_analise', 1);

        $this->logar('CRIANDO A TABELA md_utl_rel_analise_produto');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_rel_analise_produto (
				id_md_utl_rel_analise_produto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_analise ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_produto ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_rel_triagem_atv ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_serie ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
				observacao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_rel_analise_produto', 'pk_md_utl_rel_analise_produto', array('id_md_utl_rel_analise_produto'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_rel_analise_produto');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_rel_analise_produto', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_md_utl_analise'), 'md_utl_analise', array('id_md_utl_analise'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_md_utl_adm_tp_produto'), 'md_utl_adm_tp_produto', array('id_md_utl_adm_tp_produto'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_serie'), 'serie', array('id_serie'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_documento'), 'documento', array('id_documento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_utl_rel_analise_produto', 'md_utl_rel_analise_produto',
            array('id_md_utl_rel_triagem_atv'), 'md_utl_rel_triagem_atv', array('id_md_utl_rel_triagem_atv'));

        $this->logar('CRIANDO A TABELA md_utl_revisao');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_revisao (
				id_md_utl_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			    sta_encaminhamento_revisao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
			    sin_analise ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				informacoes_complementares ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_revisao', 'pk_md_utl_revisao', array('id_md_utl_revisao'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_revisao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_revisao', 1);

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_rel_revis_trg_anls (
			    id_md_utl_rel_revis_trg_anls ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			    id_md_utl_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			    id_md_utl_rel_triagem_atv ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_rel_analise_produto ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_adm_tp_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_just_revisao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				observacao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_rel_revis_trg_anls', 'pk_md_utl_rel_revis_trg_anls', array('id_md_utl_rel_revis_trg_anls'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_rel_revis_trg_anls');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_rel_revis_trg_anls', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_rel_revis_trg_anls', 'md_utl_rel_revis_trg_anls',
            array('id_md_utl_revisao'), 'md_utl_revisao', array('id_md_utl_revisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_rel_revis_trg_anls', 'md_utl_rel_revis_trg_anls',
            array('id_md_utl_rel_triagem_atv'), 'md_utl_rel_triagem_atv', array('id_md_utl_rel_triagem_atv'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_rel_revis_trg_anls', 'md_utl_rel_revis_trg_anls',
            array('id_md_utl_rel_analise_produto'), 'md_utl_rel_analise_produto', array('id_md_utl_rel_analise_produto'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_utl_rel_revis_trg_anls', 'md_utl_rel_revis_trg_anls',
            array('id_md_utl_adm_tp_revisao'), 'md_utl_adm_tp_revisao', array('id_md_utl_adm_tp_revisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_utl_rel_revis_trg_anls', 'md_utl_rel_revis_trg_anls',
            array('id_md_utl_adm_tp_just_revisao'), 'md_utl_adm_tp_just_revisao', array('id_md_utl_adm_tp_just_revisao'));

        // Tabela de Controle Geral do DSMP
        $this->logar('CRIANDO A TABELA md_utl_controle_dsmp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_controle_dsmp (
                id_md_utl_controle_dsmp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario_distribuicao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_triagem ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_analise ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_revisao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				unidade_esforco ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
				id_atendimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				dth_atual ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				tipo_acao ' . $objInfraMetaBD->tipoTextoVariavel(40) . ' NOT NULL,
				detalhe ' . $objInfraMetaBD->tipoTextoGrande() . ' NULL,
				dth_prazo_tarefa ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
				sta_atendimento_dsmp ' . $objInfraMetaBD->tipoTextoFixo(2) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_controle_dsmp', 'pk_md_utl_controle_dsmp', array('id_md_utl_controle_dsmp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_procedimento'), 'procedimento', array('id_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_unidade'), 'unidade', array('id_unidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i01_md_utl_controle_dsmp', array('id_unidade'));
        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i02_md_utl_controle_dsmp', array('id_procedimento', 'id_unidade'));
        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i03_md_utl_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_adm_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_controle_dsmp');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_controle_dsmp', 1);

        // Tabela de Histórico de Controle Geral do DSMP
        $this->logar('CRIANDO A TABELA md_utl_hist_controle_dsmp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_hist_controle_dsmp (
                id_md_utl_hist_controle_dsmp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario_distribuicao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_triagem ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_analise ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				id_md_utl_revisao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				unidade_esforco ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
				dth_atual ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				id_atendimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				tipo_acao ' . $objInfraMetaBD->tipoTextoVariavel(40) . ' NOT NULL,
				detalhe ' . $objInfraMetaBD->tipoTextoGrande() . ' NULL,
				dth_prazo_tarefa ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
				sta_atendimento_dsmp ' . $objInfraMetaBD->tipoTextoFixo(2) . ' NOT NULL,
				sin_ultima_fila ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_ultimo_responsavel ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_hist_controle_dsmp', 'pk_md_utl_hist_controle_dsmp', array('id_md_utl_hist_controle_dsmp'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_procedimento'), 'procedimento', array('id_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_unidade'), 'unidade', array('id_unidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i01_md_utl_hist_controle_dsmp', array('id_unidade'));
        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i02_md_utl_hist_controle_dsmp', array('id_procedimento', 'id_unidade'));
        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i03_md_utl_hist_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_adm_fila'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_hist_controle_dsmp');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_hist_controle_dsmp', 1);

        //Triagem
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_md_utl_triagem'), 'md_utl_triagem', array('id_md_utl_triagem'));
        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i04_md_utl_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_triagem'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_md_utl_triagem'), 'md_utl_triagem', array('id_md_utl_triagem'));
        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i04_md_utl_hist_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_triagem'));

        //Analise
        $objInfraMetaBD->adicionarChaveEstrangeira('fk7_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_md_utl_analise'), 'md_utl_analise', array('id_md_utl_analise'));
        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i05_md_utl_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_analise'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk7_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_md_utl_analise'), 'md_utl_analise', array('id_md_utl_analise'));
        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i05_md_utl_hist_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_analise'));

        //Revisao
        $objInfraMetaBD->adicionarChaveEstrangeira('fk8_md_utl_controle_dsmp', 'md_utl_controle_dsmp',
            array('id_md_utl_revisao'), 'md_utl_revisao', array('id_md_utl_revisao'));
        $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i06_md_utl_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_revisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk8_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp',
            array('id_md_utl_revisao'), 'md_utl_revisao', array('id_md_utl_revisao'));
        $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i06_md_utl_hist_controle_dsmp', array('id_procedimento', 'id_unidade', 'id_md_utl_revisao'));

        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( \'1.0.0\',  \'' . $this->nomeParametroModulo . '\' )');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv110()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

        //Correção Análise
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_rel_analise_produto', 'fk5_md_utl_rel_analise_produto');
        $objInfraMetaBD->adicionarColuna('md_utl_rel_analise_produto', 'valor', $objInfraMetaBD->tipoTextoVariavel(50), 'null');

        $objMdUtlRelAnaliseRN = new MdUtlRelAnaliseProdutoRN();
        $objMdUtlRelAnaliseRN->preencherProtocoloFormatadoDoc();

        //Correção de Datas para tabelas Gerais

        //Triagem
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_triagem', 'md_utl_triagem', array('id_usuario'), 'usuario', array('id_usuario'));

        //Analise
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_analise', 'md_utl_analise', array('id_usuario'), 'usuario', array('id_usuario'));

        //Revisao
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_revisao', 'md_utl_revisao', array('id_usuario'), 'usuario', array('id_usuario'));

        //Correção do Histórico
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'dth_final', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'sin_acao_concluida', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $objMdUtlHistCtrlDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistCtrlDsmpRN->preencherCamposGeraisControleDesempenho();

        $objInfraMetaBD->alterarColuna('md_utl_hist_controle_dsmp', 'dth_final', $objInfraMetaBD->tipoDataHora(), 'not null');
        $objInfraMetaBD->alterarColuna('md_utl_hist_controle_dsmp', 'sin_acao_concluida', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.1.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv120()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

        $this->logar('CRIANDO USUÁRIO DO MÓDULO DE UTILIDADES');
        $objRN = new MdUtlUsuarioRN();
        $objRN->realizarInsercoesUsuarioModuloUtl();

        $this->logar('ALTERANDO para not null as Datas finais e Usuários da tabela de Triagem, Revisão e Análise');
        $objMdUtlTriagemRN = new MdUtlTriagemRN();
        $objMdUtlAnaliseRN = new MdUtlAnaliseRN();
        $objMdUtlRevisaoRN = new MdUtlRevisaoRN();

        $objUsuarioRN = new MdUtlUsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();
        $idUsuario = $objUsuarioDTO->getNumIdUsuario();

        $objMdUtlTriagemRN->checarDadosTriagem($idUsuario);
        $objMdUtlAnaliseRN->checarDadosAnalise($idUsuario);
        $objMdUtlRevisaoRN->checarDadosRevisao($idUsuario);

        //MduTriagem
        $objInfraMetaBD->alterarColuna('md_utl_triagem', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'not null');
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_triagem', 'fk2_md_utl_triagem');
        $objInfraMetaBD->excluirIndice('md_utl_triagem', 'fk2_md_utl_triagem');
        $objInfraMetaBD->alterarColuna('md_utl_triagem', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'not null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_triagem', 'md_utl_triagem', array('id_usuario'), 'usuario', array('id_usuario'));

        //MdUtlAnalise
        $objInfraMetaBD->alterarColuna('md_utl_analise', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'not null');
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_analise', 'fk2_md_utl_analise');
        $objInfraMetaBD->excluirIndice('md_utl_analise', 'fk2_md_utl_analise');
        $objInfraMetaBD->alterarColuna('md_utl_analise', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'not null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_analise', 'md_utl_analise', array('id_usuario'), 'usuario', array('id_usuario'));

        //MdUtlRevisao
        $objInfraMetaBD->alterarColuna('md_utl_revisao', 'dth_atual', $objInfraMetaBD->tipoDataHora(), 'not null');
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_revisao', 'fk1_md_utl_revisao');
        $objInfraMetaBD->excluirIndice('md_utl_revisao', 'fk1_md_utl_revisao');
        $objInfraMetaBD->alterarColuna('md_utl_revisao', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'not null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_revisao', 'md_utl_revisao', array('id_usuario'), 'usuario', array('id_usuario'));

        $this->logar('ALTERANDO A TABELA md_utl_adm_prm_gr');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'resp_tacita_dilacao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'resp_tacita_suspensao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'resp_tacita_interrupcao', $objInfraMetaBD->tipoTextoFixo(3), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'prazo_max_suspensao', $objInfraMetaBD->tipoNumero(), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'prazo_max_interrupcao', $objInfraMetaBD->tipoNumero(), 'NULL');

        $this->logar('ALTERANDO A TABELA md_utl_adm_fila');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_fila', 'resp_tacita_dilacao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');

        $this->logar('ALTERANDO A TABELA md_utl_adm_just_prazo');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_just_prazo', 'sin_dilacao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_just_prazo', 'sin_suspensao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_just_prazo', 'sin_interrupcao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');

        $this->logar('CRIANDO A TABELA md_utl_ajuste_prazo');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_ajuste_prazo (
                id_md_utl_ajuste_prazo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_tipo_solicitacao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				dth_prazo_solicitacao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				dth_prazo_inicial ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
				id_md_utl_adm_just_prazo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_solicitacao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				observacao ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				dias_uteis_excedentes ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_ajuste_prazo', 1);

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_ajuste_prazo', 'pk_md_utl_ajuste_prazo', array('id_md_utl_ajuste_prazo'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_ajuste_prazo', 'md_utl_ajuste_prazo', array('id_md_utl_adm_just_prazo'), 'md_utl_adm_just_prazo', array('id_md_utl_adm_just_prazo'));

        $this->logar('ALTERANDO A TABELA md_utl_hist_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'id_md_utl_ajuste_prazo', $objInfraMetaBD->tipoNumero(), 'NULL');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk9_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp', array('id_md_utl_ajuste_prazo'), 'md_utl_ajuste_prazo', array('id_md_utl_ajuste_prazo'));

        $this->logar('ALTERANDO A TABELA md_utl_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'id_md_utl_ajuste_prazo', $objInfraMetaBD->tipoNumero(), 'NULL');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk9_md_utl_controle_dsmp', 'md_utl_controle_dsmp', array('id_md_utl_ajuste_prazo'), 'md_utl_ajuste_prazo', array('id_md_utl_ajuste_prazo'));

        /*Atualizando o valor iniciar de Justificativa de Prazo */
        $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
        $objMdUtlAdmJustPrazoDTO->retTodos();
        $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
        $count = $objMdUtlAdmJustPrazoRN->contar($objMdUtlAdmJustPrazoDTO);
        if ($count > 0) {
            $arrObjs = $objMdUtlAdmJustPrazoRN->listar($objMdUtlAdmJustPrazoDTO);

            foreach ($arrObjs as $objDTO) {
                $objDTO->setStrSinDilacao('N');
                $objDTO->setStrSinInterrupcao('N');
                $objDTO->setStrSinSuspensao('N');
                $objMdUtlAdmJustPrazoRN->alterar($objDTO);
            }
        }

        //Alterando os campos para not null
        $objInfraMetaBD->alterarColuna('md_utl_adm_just_prazo', 'sin_dilacao', $objInfraMetaBD->tipoTextoFixo(1), 'NOT NULL');
        $objInfraMetaBD->alterarColuna('md_utl_adm_just_prazo', 'sin_suspensao', $objInfraMetaBD->tipoTextoFixo(1), 'NOT NULL');
        $objInfraMetaBD->alterarColuna('md_utl_adm_just_prazo', 'sin_interrupcao', $objInfraMetaBD->tipoTextoFixo(1), 'NOT NULL');

        $strDescricao = 'Script para Reprovação/Aprovação dos Ajustes de Prazo';
        $strComando = 'MdUtlAgendamentoAutomaticoRN::aprovarReprovarAjustesPrazo';
        $this->_cadastrarNovoAgendamento($strDescricao, $strComando);

        //Iniciando as alteração da 1.2
        $objInfraMetaBD->alterarColuna('md_utl_hist_controle_dsmp', 'tipo_acao', $objInfraMetaBD->tipoTextoVariavel(100), 'not null');
        $objInfraMetaBD->alterarColuna('md_utl_controle_dsmp', 'tipo_acao', $objInfraMetaBD->tipoTextoVariavel(100), 'not null');

        $this->logar('CRIANDO A TABELA md_utl_adm_hist_prm_gr_usu');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_hist_prm_gr_usu (
				id_md_utl_adm_hist_prm_gr_usu ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_prm_gr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_tipo_presenca ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				fator_desemp_diferenciado ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				sta_tipo_jornada ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				fator_reducao_jornada ' . $objInfraMetaBD->tipoNumero() . ' NULL,
				dth_inicial ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
				dth_final ' . $objInfraMetaBD->tipoDataHora() . ' NULL, 
				id_usuario_atual ' . $objInfraMetaBD->tipoNumero() . ' NULL) '
        );

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_hist_prm_gr_usu', 1);
        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_hist_prm_gr_usu', 'pk_md_utl_adm_hist_prm_gr_usu', array('id_md_utl_adm_hist_prm_gr_usu'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_hist_prm_gr_usu',
            array('id_md_utl_adm_prm_gr'), 'md_utl_adm_prm_gr', array('id_md_utl_adm_prm_gr'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_hist_prm_gr_usu',
            array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_hist_prm_gr_usu',
            array('id_usuario_atual'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'inicio_periodo', $objInfraMetaBD->tipoNumero(), 'null');

        $objMdUtlAdmHistPrmGrUsuRN = new MdUtlAdmHistPrmGrUsuRN();
        $objMdUtlAdmHistPrmGrUsuRN->migrarDadosExistentesParamHistorico();

        $objInfraMetaBD->alterarColuna('md_utl_adm_hist_prm_gr_usu', 'dth_inicial', $objInfraMetaBD->tipoDataHora(), 'not null');
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_adm_hist_prm_gr_usu', 'fk3_md_utl_adm_hist_prm_gr_usu');
        $objInfraMetaBD->excluirIndice('md_utl_adm_hist_prm_gr_usu', 'fk3_md_utl_adm_hist_prm_gr_usu');
        $objInfraMetaBD->alterarColuna('md_utl_adm_hist_prm_gr_usu', 'id_usuario_atual', $objInfraMetaBD->tipoNumero(), 'not null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_hist_prm_gr_usu', array('id_usuario_atual'), 'usuario', array('id_usuario'));

        $objMdUtlAtividadeRN = new MdUtlAdmAtividadeRN();
        $objMdUtlAtividadeRN->preencherCorretamenteHabilitarRevisao();

        $objInfraMetaBD->alterarColuna('md_utl_adm_atividade', 'sin_atv_rev_amostragem', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        $objInfraMetaBD->alterarColuna('md_utl_adm_fila_prm_gr_usu', 'percentual_revisao', $objInfraMetaBD->tipoNumero(), 'null');

        $objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
        $objMdUtlAdmFilaPrmGrUsuRN->alterarDadosTipoRevisao();

        $strDescricao = 'Script para retornar o Status no Final da Suspensão ou Interrupção';
        $strComando = 'MdUtlAgendamentoAutomaticoRN::retornarStatusFinal';
        $this->_cadastrarNovoAgendamento($strDescricao, $strComando);

        $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
        $objMdUtlAdmPrmGrRN->parametrizaInicioFimDoPeriodo();

        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'sin_associar_fila', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'id_md_utl_adm_fila', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_revisao', 'md_utl_revisao', array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $strDescricao = 'Script Responsável por Associar Processos a Fila de forma Automática.';
        $strComando = 'MdUtlAgendamentoAutomaticoRN::associarProcessoFila';
        $strPeriodicidadeComplemento = '7,8,9,10,11,12,13,14,15,16,17,18,19,20';
        $this->_cadastrarNovoAgendamento($strDescricao, $strComando, $strPeriodicidadeComplemento);

        $objInfraMetaBD->alterarColuna('md_utl_adm_prm_gr', 'sin_retorno_ult_fila', $objInfraMetaBD->tipoTextoFixo(1), null);

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpRN->corrigirCampoUltimaFila();

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.2.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv130()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

        SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_listar');

        //Justificativa de Contestação
        $this->logar('CRIANDO A TABELA md_utl_adm_just_contest');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_just_contest (
                id_md_utl_adm_just_contest ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
				descricao ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_just_contest', 'pk_md_utl_adm_just_contest', array('id_md_utl_adm_just_contest'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_just_contest', 'md_utl_adm_just_contest',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_just_contest');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_just_contest', 1);

        //Solicitar Contestação
        $this->logar('CRIANDO A TABELA md_utl_adm_prm_contest');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_prm_contest (
                id_md_utl_adm_prm_contest ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sin_reprovacao_automatica ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				qtd_dias_uteis_reprovacao ' . $objInfraMetaBD->tipoNumero() . ' NULL
				) '
        );

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_prm_contest', 'md_utl_adm_prm_contest',
            array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_prm_contest', 1);

        //Parametrização da Contestação
        $this->logar('CRIANDO A TABELA md_utl_contest_revisao');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_contest_revisao (
                id_md_utl_contest_revisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_just_contest ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_solicitacao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				informacoes_complementares ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
				) '
        );

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_contest_revisao', 1);
        $objInfraMetaBD->adicionarChavePrimaria('md_utl_contest_revisao', 'pk_md_utl_contest_revisao', array('id_md_utl_contest_revisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_contest_revisao', 'md_utl_contest_revisao',
            array('id_md_utl_adm_just_contest'), 'md_utl_adm_just_contest', array('id_md_utl_adm_just_contest'));

        $this->logar('ALTERANDO A TABELA md_utl_hist_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'id_md_utl_contest_revisao', $objInfraMetaBD->tipoNumero(), 'NULL');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk10_md_utl_hist_controle_dsmp', 'md_utl_hist_controle_dsmp', array('id_md_utl_contest_revisao'), 'md_utl_contest_revisao', array('id_md_utl_contest_revisao'));

        $this->logar('ALTERANDO A TABELA md_utl_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'id_md_utl_contest_revisao', $objInfraMetaBD->tipoNumero(), 'NULL');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk10_md_utl_controle_dsmp', 'md_utl_controle_dsmp', array('id_md_utl_contest_revisao'), 'md_utl_contest_revisao', array('id_md_utl_contest_revisao'));

        //Parametrização da Distribuição
        $this->logar('CRIANDO A TABELA md_utl_adm_prm_ds');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_prm_ds (
                id_md_utl_adm_prm_ds ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_tp_ctrl_desemp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sin_priorizar_distribuicao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_fila ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_status_atendimento_dsmp ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_atividade ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
        );

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_prm_ds');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_prm_ds', 1);

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_prm_ds', 'pk_md_utl_adm_prm_ds', array('id_md_utl_adm_prm_ds'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_prm_ds', 'md_utl_adm_prm_ds', array('id_md_utl_adm_tp_ctrl_desemp'), 'md_utl_adm_tp_ctrl_desemp', array('id_md_utl_adm_tp_ctrl_desemp'));

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_prm_ds_fila');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_prm_ds_fila (
                id_md_utl_adm_prm_ds ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_fila ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				prioridade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_prm_ds_fila', 'pk_md_utl_adm_rel_prm_ds_fila', array('id_md_utl_adm_prm_ds', 'id_md_utl_adm_fila'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_prm_ds_fila', 'md_utl_adm_rel_prm_ds_fila', array('id_md_utl_adm_prm_ds'), 'md_utl_adm_prm_ds', array('id_md_utl_adm_prm_ds'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_prm_ds_fila', 'md_utl_adm_rel_prm_ds_fila', array('id_md_utl_adm_fila'), 'md_utl_adm_fila', array('id_md_utl_adm_fila'));

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_prm_ds_ativ');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_prm_ds_ativ (
                id_md_utl_adm_prm_ds ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_atividade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				prioridade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_prm_ds_ativ', 'pk_md_utl_adm_rel_prm_ds_ativ', array('id_md_utl_adm_prm_ds', 'id_md_utl_adm_atividade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_prm_ds_ativ', 'md_utl_adm_rel_prm_ds_ativ', array('id_md_utl_adm_prm_ds'), 'md_utl_adm_prm_ds', array('id_md_utl_adm_prm_ds'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_rel_prm_ds_ativ', 'md_utl_adm_rel_prm_ds_ativ', array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_prm_ds_aten');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_prm_ds_aten (
                id_md_utl_adm_prm_ds ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				sta_atendimento_dsmp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				prioridade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_prm_ds_aten', 'pk_md_utl_adm_rel_prm_ds_aten', array('id_md_utl_adm_prm_ds', 'sta_atendimento_dsmp'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_prm_ds_aten', 'md_utl_adm_rel_prm_ds_aten', array('id_md_utl_adm_prm_ds'), 'md_utl_adm_prm_ds', array('id_md_utl_adm_prm_ds'));

        //Revisão
        $this->logar('CRIANDO colunas na tabela md_utl_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'sta_encaminhamento_contestacao', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->alterarColuna('md_utl_revisao', 'sta_encaminhamento_revisao', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        //Contestacao
        $this->logar('CRIANDO colunas na tabela md_utl_contest_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_contest_revisao', 'id_md_utl_revisao', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_contest_revisao', 'md_utl_contest_revisao', array('id_md_utl_revisao'), 'md_utl_revisao', array('id_md_utl_revisao'));

        $strDescricao = 'Script Responsável por Reprovar as Contestações de Revisão após o Vencimento do Prazo.';
        $strComando = 'MdUtlAgendamentoAutomaticoRN::reprovarContestacao';
        $strPeriodicidadeComplemento = '1';
        $this->_cadastrarNovoAgendamento($strDescricao, $strComando, $strPeriodicidadeComplemento);

        $arrTabelas = array('md_utl_adm_atividade', 'md_utl_adm_atv_serie_prod', 'md_utl_adm_fila', 'md_utl_adm_fila_prm_gr_usu', 'md_utl_adm_grp', 'md_utl_adm_grp_fila', 'md_utl_adm_grp_fila_proc', 'md_utl_adm_grp_fl_proc_atv', 'md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_jornada', 'md_utl_adm_just_contest', 'md_utl_adm_just_prazo', 'md_utl_adm_prm_contest', 'md_utl_adm_prm_ds', 'md_utl_adm_prm_gr', 'md_utl_adm_prm_gr_usu', 'md_utl_adm_rel_jornada_usu', 'md_utl_adm_rel_prm_ds_aten', 'md_utl_adm_rel_prm_ds_ativ', 'md_utl_adm_rel_prm_ds_fila', 'md_utl_adm_rel_prm_gr_proc', 'md_utl_adm_rel_tp_ctrl_und', 'md_utl_adm_rel_tp_ctrl_usu', 'md_utl_adm_tp_ausencia', 'md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_tp_just_revisao', 'md_utl_adm_tp_produto', 'md_utl_adm_tp_revisao', 'md_utl_ajuste_prazo', 'md_utl_analise', 'md_utl_contest_revisao', 'md_utl_controle_dsmp', 'md_utl_hist_controle_dsmp', 'md_utl_rel_analise_produto', 'md_utl_rel_revis_trg_anls', 'md_utl_rel_triagem_atv', 'md_utl_revisao', 'md_utl_triagem');

        $this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.3.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv140()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.4.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv150()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

        $this->logar('CRIANDO A TABELA md_utl_adm_rel_prm_ds_proc');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_rel_prm_ds_proc (
                id_md_utl_adm_prm_ds ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				id_md_utl_adm_prm_gr_proc ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				prioridade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_rel_prm_ds_proc', 'pk_md_utl_adm_rel_prm_ds_proc', array('id_md_utl_adm_prm_ds', 'id_md_utl_adm_prm_gr_proc'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_rel_prm_ds_proc', 'md_utl_adm_rel_prm_ds_proc', array('id_md_utl_adm_prm_ds'), 'md_utl_adm_prm_ds', array('id_md_utl_adm_prm_ds'));

        $this->logar('CRIANDO colunas na tabela md_utl_adm_prm_ds');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'sin_tipo_processo', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'sin_dias_uteis', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'distribuicao_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'fila_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'status_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'atividade_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'tipo_processo_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'dias_uteis_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_ds', 'qtd_dias_uteis', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'unidade_esforco', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'dth_inicio', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'dth_prazo', $objInfraMetaBD->tipoDataHora(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_triagem');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'unidade_esforco', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'dth_inicio', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'dth_prazo', $objInfraMetaBD->tipoDataHora(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_analise');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'unidade_esforco', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'dth_inicio', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'dth_prazo', $objInfraMetaBD->tipoDataHora(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'sta_atribuido', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_hist_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'sta_atribuido', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $this->_atualizarHistControleDsmp();

        $this->logar('ALTERANDO A TABELA - adicionado md_utl_adm_atividade.complexidade');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_atividade', 'complexidade', $objInfraMetaBD->tipoNumero(), 'NULL');

        $this->logar('ATUALIZANDO A TABELA - populando novo campo campo complexidade com valor 0');
        $sqlTabela = ' UPDATE md_utl_adm_atividade SET complexidade=0 WHERE complexidade IS NULL ';
        BancoSEI::getInstance()->executarSql($sqlTabela);

        $this->logar('ALTERANDO A TABELA - alterando md_utl_adm_atividade.complexidade para NOT NULL');
        $objInfraMetaBD->alterarColuna('md_utl_adm_atividade', 'complexidade', $objInfraMetaBD->tipoNumero(), 'NOT NULL');

        $this->logar('INSERINDO PARÂMETROS DE BLOQUEIO DO MÓDULO ');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_BLOQUEAR_GERAR_PROCESSO_SEM_PELO_MENOS_UM_INTERESSADO\' )');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_BLOQUEAR_ANEXAR_PROCESSO_COM_DOCUMENTO_NAO_ASSINADO\' )');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_BLOQUEAR_CONCLUIR_PROCESSO_COM_DOCUMENTO_NAO_ASSINADO\' )');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_BLOQUEAR_CONCLUIR_PROCESSO_COM_DOCUMENTO_RESTRITO_USANDO_HIPOTESE_LEGAL\' )');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_ID_GRUPOS_CONTATO_TRAVAR_CONTATOS\' )');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_ID_TIPO_DOCUMENTO_EXIGIDO_CANCELAR\' )');

        $this->logar('RENOMEANDO coluna na tabela md_utl_controle_dsmp de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_controle_dsmp set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_controle_dsmp', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_hist_controle_dsmp de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_hist_controle_dsmp set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_hist_controle_dsmp', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_triagem de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_triagem set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_triagem', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_analise de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_analise set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_analise', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_revisao de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_revisao set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_revisao', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_adm_atividade de und_esforco_atv para tmp_execucao_atv');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_atividade', 'tmp_execucao_atv', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_atividade set tmp_execucao_atv=und_esforco_atv');
        $objInfraMetaBD->excluirColuna('md_utl_adm_atividade', 'und_esforco_atv');

        $this->logar('RENOMEANDO coluna na tabela md_utl_adm_atv_serie_prod de und_esforco_rev_produto para tmp_execucao_rev_produto');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_atv_serie_prod', 'tmp_execucao_rev_produto', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_atv_serie_prod set tmp_execucao_rev_produto=und_esforco_rev_produto');
        $objInfraMetaBD->excluirColuna('md_utl_adm_atv_serie_prod', 'und_esforco_rev_produto');

        $this->logar('RENOMEANDO coluna na tabela md_utl_rel_triagem_atv de unidade_esforco para tempo_execucao');
        $objInfraMetaBD->adicionarColuna('md_utl_rel_triagem_atv', 'tempo_execucao', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_rel_triagem_atv set tempo_execucao=unidade_esforco');
        $objInfraMetaBD->excluirColuna('md_utl_rel_triagem_atv', 'unidade_esforco');

        $this->logar('RENOMEANDO coluna na tabela md_utl_adm_atividade de und_esforco_rev para tmp_execucao_rev');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_atividade', 'tmp_execucao_rev', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_atividade set tmp_execucao_rev=und_esforco_rev');
        $objInfraMetaBD->excluirColuna('md_utl_adm_atividade', 'und_esforco_rev');

        $this->logar('RENOMEANDO coluna na tabela md_utl_adm_fila de und_esforco_triagem para tmp_execucao_triagem');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_fila', 'tmp_execucao_triagem', $objInfraMetaBD->tipoNumero(), 'NULL');
        BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_fila set tmp_execucao_triagem=und_esforco_triagem');
        $objInfraMetaBD->excluirColuna('md_utl_adm_fila', 'und_esforco_triagem');

        $this->logar('CRIANDO colunas na md_utl_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'sta_tipo_presenca', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'tempo_de_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_controle_dsmp', 'percentual_desempenho', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('CRIANDO colunas na md_utl_hist_controle_dsmp');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'sta_tipo_presenca', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'tempo_de_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_hist_controle_dsmp', 'percentual_desempenho', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'sta_tipo_presenca', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'tempo_de_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'percentual_desempenho', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_analise');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'sta_tipo_presenca', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'tempo_de_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'percentual_desempenho', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_triagem');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'sta_tipo_presenca', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'tempo_de_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'percentual_desempenho', $objInfraMetaBD->tipoNumero(), 'null');

        $this->logar('ADICIONANDO colunas na tabela md_utl_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'avaliacao_qualitativa', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'sin_realizar_aval_prod_prod', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $this->logar('Ajuste de dados na tabela md_utl_revisao');
        $this->atualizarSinRealizarAvalProdProd();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_revisao');

        $this->logar('ALTERANDO colunas na tabela md_utl_revisao para not null');
        $objInfraMetaBD->alterarColuna('md_utl_revisao', 'sin_realizar_aval_prod_prod', $objInfraMetaBD->tipoTextoFixo(1), 'not null');


        $this->logar('ALTERANDO tablea md_utl_adm_fila coluna prazo_tarefa de not null para nul');
        $objInfraMetaBD->alterarColuna('md_utl_adm_fila', 'prazo_tarefa', $objInfraMetaBD->tipoTextoVariavel(3), 'null');

        $this->logar('Ajuste de dados na tabela md_utl_hist_controle_dsmp');
        $this->atualizarTipoAcaoMdUtlHistControleDsmpPercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_hist_controle_dsmp');

        $this->logar('Ajuste de dados na tabela md_utl_triagem');
        $this->atualizarCamposTriagemNull();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_triagem');

        $this->logar('Ajuste de dados na tabela md_utl_analise');
        $this->atualizarCamposAnaliseTempoExecucaoNull();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_analise');

        $this->logar('Ajuste de dados na tabela md_utl_revisao');
        $this->atualizarCamposRevisaoTempoExecucaoNull();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_revisao');

        $this->logar('Montagem dados a serem inseridos na tabela md_utl_triagem');
        $this->atualizarMdUtlTriagemPercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_triagem');

        $this->logar('Montagem dados a serem inseridos na tabela md_utl_analise');
        $this->atualizarMdUtlAnalisePercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_analise');

        $this->logar('Montagem dados a serem inseridos na tabela md_utl_revisao');
        $this->atualizarMdUtlRevisaoPercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_revisao');

        $this->logar('Montagem dados a serem inseridos na tabela md_utl_hist_controle_dsmp');
        $this->atualizarMdUtlHistControleDsmpPercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_hist_controle_dsmp');

        $this->logar('Montagem dados a serem inseridos na tabela md_utl_controle_dsmp');
        $this->atualizarMdUtlControleDsmpPercentualDesempenho();
        $this->logar('FIM da Montagem dados a serem inseridos na tabela md_utl_controle_dsmp');

        $this->logar('ALTERAÇÃO NO TAMANHO DA COLUNA OBSERVACAO, TABELA: MD_UTL_REL_ANALISE_PRODUTO');
        $objInfraMetaBD->alterarColuna('md_utl_rel_analise_produto', 'observacao', $objInfraMetaBD->tipoTextoVariavel(500), 'null');
        $this->logar('FIM DA ALTERAÇÃO NO TAMANHO DA COLUNA OBSERVACAO, TABELA: MD_UTL_REL_ANALISE_PRODUTO');

        // altera os dados substituindo tipo_acao de "Revisão para "Avaliação"
        $this->logar('Altera os dados substituindo tipo_acao de "Revisão para "Avaliação"');
        $this->replaceRevisaoParaAvaliacao();
        $this->logar('FIM da Alteração dos dados substituindo tipo_acao de "Revisão para "Avaliação" ');

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.5.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv200()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

        $this->logar('Cria a coluna sin_nao_aplicar_perc_dsmp na tabela md_utl_adm_atividade');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_atividade', 'sin_nao_aplicar_perc_dsmp', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql("UPDATE md_utl_adm_atividade set sin_nao_aplicar_perc_dsmp='N'");
        $this->logar('FIM da Criação da coluna sin_nao_aplicar_perc_dsmp na tabela md_utl_adm_atividade');

        $this->logar('Cria a coluna tempo_execucao_atribuido na tabela md_utl_rel_triagem_atv');
        $objInfraMetaBD->adicionarColuna('md_utl_rel_triagem_atv', 'tempo_execucao_atribuido', $objInfraMetaBD->tipoNumero(), 'null');
        $this->logar('FIM da Criação da coluna tempo_execucao_atribuido na tabela md_utl_rel_triagem_atv');

        $this->logar('Inserindo a coluna');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_tp_ctrl_desemp', 'id_serie', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_tp_ctrl_desemp', array('id_serie'), 'serie', array('id_serie'));
        $this->logar('FIM da inserção da coluna');

        $this->logar('Cria a coluna data de corte na tabela md_utl_adm_prm_gr');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr', 'dta_corte', $objInfraMetaBD->tipoDataHora(), 'null');
        $this->logar('FIM da criação a coluna data de corte na tabela md_utl_adm_prm_gr');

        $this->logar('Inserindo a coluna id_documento na Tabela md_utl_adm_prm_gr_usu');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu', 'id_documento', $objInfraMetaBD->tipoNumeroGrande(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_utl_adm_prm_gr_usu', 'md_utl_adm_prm_gr_usu', array('id_documento'), 'documento', array('id_documento'));
        $this->logar('FIM da inserção da coluna');

        $this->logar('Inserindo a coluna id_documento na Tabela md_utl_adm_hist_prm_gr_usu');
        $objInfraMetaBD->adicionarColuna('md_utl_adm_hist_prm_gr_usu', 'id_documento', $objInfraMetaBD->tipoNumeroGrande(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_hist_prm_gr_usu', array('id_documento'), 'documento', array('id_documento'));
        $this->logar('FIM da inserção da coluna');

        $this->logar('Popular tempo atribuido na tabela md_utl_rel_triagem_atv');
        $this->atualizarTempoAtribuidoAtv();
        $this->logar('FIM popular tempo atribuido na tabela md_utl_rel_triagem_atv');

        $this->logar('Cria a coluna protocolo_formatado na tabela md_utl_rel_analise_produto.');
        $objInfraMetaBD->adicionarColuna('md_utl_rel_analise_produto', 'protocolo_formatado', $objInfraMetaBD->tipoTextoVariavel(50), 'null');
        $this->logar('Criado coluna protocolo_formatado na tabela md_utl_rel_analise_produto.');

        $this->logar('Popula dados legados na nova coluna protocolo_formatado.');
        $this->populaDadosLegadoDocumento();
        $this->logar('Populado os registros legados.');

        $this->logar('Excluir coluna id_documento da tabela md_utl_rel_analise_produto.');
	      $objInfraMetaBD->excluirIndice('md_utl_rel_analise_produto', 'fk5_md_utl_rel_analise_produto');
        $objInfraMetaBD->excluirColuna('md_utl_rel_analise_produto', 'id_documento');
        $this->logar('Coluna id_documento da tabela md_utl_rel_analise_produto excluída.');

        $this->logar('Excluir coluna valor da tabela md_utl_rel_analise_produto, pois não existe utilidade para esta versão.');
        $objInfraMetaBD->excluirColuna('md_utl_rel_analise_produto', 'valor');
        $this->logar('Coluna valor da tabela md_utl_rel_analise_produto excluída.');

        $this->logar('Excluir coluna sta_aplicabilidade_serie da tabela md_utl_adm_atv_serie_prod.');
        $objInfraMetaBD->excluirColuna('md_utl_adm_atv_serie_prod', 'sta_aplicabilidade_serie');
        $this->logar('Coluna sta_aplicabilidade_serie da tabela md_utl_adm_atv_serie_prod excluída.');

        $this->logar('Excluir coluna id_md_utl_adm_fila da tabela md_utl_adm_prm_gr.');
        $objInfraMetaBD->excluirChaveEstrangeira('md_utl_adm_prm_gr', 'fk1_md_utl_adm_prm_gr');
	      $objInfraMetaBD->excluirIndice('md_utl_adm_prm_gr', 'fk1_md_utl_adm_prm_gr');
        $objInfraMetaBD->excluirColuna('md_utl_adm_prm_gr', 'id_md_utl_adm_fila');
        $this->logar('Coluna id_md_utl_adm_fila da tabela md_utl_adm_prm_gr excluída.');

        # ------------------------

        $this->logar('Cria a coluna sin_dist_auto_para_mim na tabela md_utl_triagem');
        $objInfraMetaBD->adicionarColuna('md_utl_triagem', 'sin_dist_auto_para_mim', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $this->logar('Cria a coluna sin_dist_auto_para_mim na tabela md_utl_analise');
        $objInfraMetaBD->adicionarColuna('md_utl_analise', 'sin_dist_auto_para_mim', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        $this->logar('Cria a coluna sin_dist_auto_triag_analista na tabela md_utl_revisao');
        $objInfraMetaBD->adicionarColuna('md_utl_revisao', 'sin_dist_auto_triag_analista', $objInfraMetaBD->tipoTextoFixo(1), 'null');

        # ------------------------

        //$this->logar('INSERINDO PARÂMETROS DE BLOQUEIO DO MÓDULO ');
        //BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( null,  \'MODULO_UTILIDADES_BLOQUEAR_BLOQUEAR_PROCESSO_COM_DOCUMENTO_RESTRITO_USANDO_HIPOTESE_LEGAL\' )');

        $arrTabelas= array('md_utl_adm_atividade', 'md_utl_adm_atv_serie_prod', 'md_utl_adm_fila', 'md_utl_adm_fila_prm_gr_usu', 'md_utl_adm_grp', 'md_utl_adm_grp_fila', 'md_utl_adm_grp_fila_proc', 'md_utl_adm_grp_fl_proc_atv', 'md_utl_adm_hist_prm_gr_usu', 'md_utl_adm_jornada', 'md_utl_adm_just_contest', 'md_utl_adm_just_prazo', 'md_utl_adm_prm_contest', 'md_utl_adm_prm_ds', 'md_utl_adm_prm_gr', 'md_utl_adm_prm_gr_usu', 'md_utl_adm_rel_jornada_usu', 'md_utl_adm_rel_prm_ds_aten', 'md_utl_adm_rel_prm_ds_ativ', 'md_utl_adm_rel_prm_ds_fila', 'md_utl_adm_rel_prm_ds_proc', 'md_utl_adm_rel_prm_gr_proc', 'md_utl_adm_rel_tp_ctrl_und', 'md_utl_adm_rel_tp_ctrl_usu', 'md_utl_adm_tp_ausencia', 'md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_tp_just_revisao', 'md_utl_adm_tp_produto', 'md_utl_adm_tp_revisao', 'md_utl_ajuste_prazo', 'md_utl_analise', 'md_utl_contest_revisao', 'md_utl_controle_dsmp', 'md_utl_hist_controle_dsmp', 'md_utl_rel_analise_produto', 'md_utl_rel_revis_trg_anls', 'md_utl_rel_triagem_atv', 'md_utl_revisao', 'md_utl_triagem');

        $this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'2.0.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    protected function instalarv210()
    {
	    $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.1.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

	    $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
	    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_listar');

	    $this->logar('Inserindo a coluna chefia_imediata na Tabela md_utl_adm_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu', 'chefia_imediata', $objInfraMetaBD->tipoTextoFixo(1), 'null');
	    $this->logar('FIM da inserção da chefia_imediata na tabela md_utl_adm_prm_gr_usu');

	    $this->logar('Inserindo a coluna chefia_imediata na Tabela md_utl_adm_hist_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_hist_prm_gr_usu', 'chefia_imediata', $objInfraMetaBD->tipoTextoFixo(1), 'null');
	    $this->logar('FIM da inserção da chefia_imediata md_utl_adm_hist_prm_gr_usu');

	    //NOVAS COLUNAS: DATA INICIO E FIM PARTICIPACAO
	    $this->logar('Inserindo a coluna dth_ini_participacao na Tabela md_utl_adm_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu', 'dth_ini_participacao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dth_ini_participacao');

	    $this->logar('Inserindo a coluna dth_fim_participacao na Tabela md_utl_adm_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu', 'dth_fim_participacao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dth_fim_participacao');

	    $this->logar('Inserindo a coluna dth_ini_participacao na Tabela md_utl_adm_hist_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_hist_prm_gr_usu', 'dth_ini_participacao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dth_ini_participacao');

	    $this->logar('Inserindo a coluna dth_fim_participacao na Tabela md_utl_adm_hist_prm_gr_usu');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_hist_prm_gr_usu', 'dth_fim_participacao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dth_fim_participacao');

	    $this->logar('MUDANÇAS RELACIONADAS A REMOÇÃO DO CRUD: JORNADA E TIPO DE AUSENCIA');
	    $this->logar('DROP NAS TABELAS: md_utl_adm_rel_jornada_usu , md_utl_adm_jornada , seq_md_utl_adm_jornada , md_utl_adm_tp_ausencia , seq_md_utl_adm_tp_ausencia');
	    if (BancoSEI::getInstance() instanceof InfraOracle) {
		    BancoSEI::getInstance()->executarSql('drop sequence seq_md_utl_adm_jornada');
		    BancoSEI::getInstance()->executarSql('drop sequence seq_md_utl_adm_tp_ausencia');
	    } else {
		    BancoSEI::getInstance()->executarSql('DROP TABLE seq_md_utl_adm_jornada');
		    BancoSEI::getInstance()->executarSql('DROP TABLE seq_md_utl_adm_tp_ausencia');
	    }

	    BancoSEI::getInstance()->executarSql('DROP TABLE md_utl_adm_rel_jornada_usu');
	    BancoSEI::getInstance()->executarSql('DROP TABLE md_utl_adm_jornada');
	    BancoSEI::getInstance()->executarSql('DROP TABLE md_utl_adm_tp_ausencia');

	    // CRIA ESTRUTURA DAS TABELAS RELACIONADAS AO MAPEAMENTO DE INTEGRACAO
	    # ----------------------------------- INTEGRACAO ---------------------
	    $this->logar('CRIANDO A TABELA md_utl_adm_integracao');
	    BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_integracao (
						id_md_utl_adm_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
						nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
						funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
						tipo_integracao ' . $objInfraMetaBD->tipoTextoFixo(2) . ' NOT NULL,
						metodo_autenticacao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
						metodo_requisicao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
						formato_resposta ' . $objInfraMetaBD->tipoTextoVariavel(10) . ' NULL,
						versao_soap ' . $objInfraMetaBD->tipoTextoVariavel(5) . ' NULL,
						token_autenticacao ' . $objInfraMetaBD->tipoTextoVariavel(76) . ' NULL,
						url_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL,
						operacao_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL,
						sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
	    );

	    $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_integracao', 'pk_md_utl_adm_integracao', array('id_md_utl_adm_integracao'));

	    $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_integracao');
	    BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_integracao', 1);

	    # ----------------------------------- INTEGRACAO HEADER --------------------
	    $this->logar('CRIANDO A TABELA md_utl_adm_integ_header');
	    BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_integ_header (
							id_md_utl_adm_integ_header ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
							id_md_utl_adm_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
							atributo ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
							conteudo ' . $objInfraMetaBD->tipoTextoVariavel(200) . ' NOT NULL,
							sin_dado_confidencial ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL) '
	    );

	    $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_integ_header', 'pk_md_utl_adm_integ_header', array('id_md_utl_adm_integ_header'));

	    $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_adm_integ_header', 'md_utl_adm_integ_header',
		    array('id_md_utl_adm_integracao'), 'md_utl_adm_integracao', array('id_md_utl_adm_integracao'));

	    $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_integ_header');
	    BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_integ_header', 1);

	    # ----------------------------------- INTEGRACAO PARAMETROS ENTRADA/SAIDA----------
	    $this->logar('CRIANDO A TABELA md_utl_adm_integ_param');
	    BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_integ_param (
							id_md_utl_adm_integ_param ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
							id_md_utl_adm_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
							nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
							tp_parametro ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
							nome_campo ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL,
							identificador '. $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL)'
	    );

	    $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_integ_param', 'pk_md_utl_adm_integ_param', array('id_md_utl_adm_integ_param'));

	    $objInfraMetaBD->adicionarChaveEstrangeira('fk1_id_md_utl_adm_integ_param', 'md_utl_adm_integ_param',
		    array('id_md_utl_adm_integracao'), 'md_utl_adm_integracao', array('id_md_utl_adm_integracao'));

	    $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_integ_param');
	    BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_integ_param', 1);

	    $this->logar('Inserindo a coluna dta_periodo_inicio na Tabela md_utl_analise');
	    $objInfraMetaBD->adicionarColuna('md_utl_analise', 'dta_periodo_inicio', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dta_periodo_inicio');

	    $this->logar('Inserindo a coluna dta_periodo_fim na Tabela md_utl_analise');
	    $objInfraMetaBD->adicionarColuna('md_utl_analise', 'dta_periodo_fim', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dta_periodo_fim');

	    $this->logar('Inserindo a coluna sta_frequencia_adm_prm_gr na Tabela md_utl_analise');
	    $objInfraMetaBD->adicionarColuna('md_utl_analise', 'sta_frequencia_adm_prm_gr', $objInfraMetaBD->tipoTextoFixo(1), 'null');
	    $this->logar('FIM da inserção da sta_frequencia_adm_prm_gr');

	    $this->logar('Inserindo a coluna data_execucao na Tabela md_utl_rel_triagem_atv');
	    $objInfraMetaBD->adicionarColuna('md_utl_rel_triagem_atv', 'data_execucao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da data_execucao');

	    $this->logar('Inserindo a coluna sin_relatar_dia_dia na Tabela md_utl_analise');
	    $objInfraMetaBD->adicionarColuna('md_utl_analise', 'sin_relatar_dia_dia', $objInfraMetaBD->tipoTextoFixo(1), 'null');

	    // REMOVER COLUNA fator_desemp_diferenciado DA TABELA md_utl_adm_prm_gr_usu
	    $this->logar('Excluir coluna fator_desemp_diferenciado das tabelas [md_utl_adm_prm_gr_usu e md_utl_adm_hist_prm_gr_usu]');
	    $objInfraMetaBD->excluirColuna('md_utl_adm_prm_gr_usu', 'fator_desemp_diferenciado');
	    $objInfraMetaBD->excluirColuna('md_utl_adm_hist_prm_gr_usu', 'fator_desemp_diferenciado');

	    $this->logar('Inserindo a coluna id_usuario_avaliacao na Tabela md_utl_analise');
	    $objInfraMetaBD->adicionarColuna('md_utl_analise', 'id_usuario_avaliacao', $objInfraMetaBD->tipoNumero(), 'null');
	    $this->logar('FIM da inserção da id_usuario_avaliacao');

	    $this->logar('CRIANDO A TABELA md_utl_adm_prm_gr_usu_carg');
	    BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_adm_prm_gr_usu_carg (
                                id_md_utl_adm_prm_gr_usu_carg '. $objInfraMetaBD->tipoNumeroGrande() .' NOT NULL,
                                id_md_utl_adm_prm_gr_usu '. $objInfraMetaBD->tipoNumero() .' NOT NULL,
                                carga_horaria '. $objInfraMetaBD->tipoNumero() .' NOT NULL,
                                periodo_inicial '. $objInfraMetaBD->tipoDataHora() .' NOT NULL,
                                periodo_final '. $objInfraMetaBD->tipoDataHora() .' NOT NULL,
                                sin_ativo '. $objInfraMetaBD->tipoTextoFixo(1) .' NOT NULL) ');

	    $objInfraMetaBD->adicionarChavePrimaria('md_utl_adm_prm_gr_usu_carg', 'pk_md_utl_adm_prm_gr_usu_carg', array('id_md_utl_adm_prm_gr_usu_carg'));

	    $this->logar('CRIANDO A SEQUENCE seq_md_utl_adm_prm_gr_usu_carg');
	    BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_adm_prm_gr_usu_carg', 1);

	    $this->logar('Inserindo a coluna dth_inclusao na Tabela md_utl_adm_prm_gr_usu_carg');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu_carg', 'dth_inclusao', $objInfraMetaBD->tipoDataHora(), 'null');
	    $this->logar('FIM da inserção da dth_inclusao');

	    $this->logar('Inserindo a coluna datas_ausencias na Tabela md_utl_adm_prm_gr_usu_carg');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu_carg', 'datas_ausencias', $objInfraMetaBD->tipoTextoVariavel(1000), 'null');
	    $this->logar('FIM da inserção da datas_ausencias');

	    $this->logar('Inserindo a coluna id_usuario na Tabela md_utl_adm_prm_gr_usu_carg');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu_carg', 'id_usuario', $objInfraMetaBD->tipoNumero(), 'not null');
	    $this->logar('FIM da inserção da id_usuario');

	    $this->logar('Inserindo a coluna id_md_utl_adm_prm_gr na Tabela md_utl_adm_prm_gr_usu_carg');
	    $objInfraMetaBD->adicionarColuna('md_utl_adm_prm_gr_usu_carg', 'id_md_utl_adm_prm_gr', $objInfraMetaBD->tipoNumero(), 'not null');
	    $this->logar('FIM da inserção da id_md_utl_adm_prm_gr');

	    // REALIZA UPDATE NAS TABELAS ABAIXO
	    $this->logar('ATUALIZADO OS DADOS DA COLUNA "inicio_periodo" DE ACORDO COM O NOVO PADRÃO DEFINIDO PELO P.O.');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_prm_gr SET inicio_periodo = \'1\' WHERE sta_frequencia = \'D\' ');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_prm_gr SET inicio_periodo = \'3\' WHERE sta_frequencia = \'S\' ');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_prm_gr SET inicio_periodo = \'4\' WHERE sta_frequencia = \'M\' ');

	    $this->logar('INICIO do update na tabela: md_utl_adm_prm_gr_usu, coluna: chefia_imediata');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_prm_gr_usu SET chefia_imediata=\'N\' ');

	    $this->logar('INICIO do update na tabela: md_utl_adm_hist_prm_gr_usu, coluna: chefia_imediata');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_adm_hist_prm_gr_usu SET chefia_imediata=\'N\' ');

	    $this->logar('INICIO do update na tabela: md_utl_analise, coluna: sin_relatar_dia_dia');
	    BancoSEI::getInstance()->executarSql('UPDATE md_utl_analise SET sin_relatar_dia_dia = \'N\' ');

	    // CADASTRAR TRES NOVOS AGENDAMENTOS DO MODULO
	    $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

	    $this->logar('SCRIPT AGENDAMENTO ATUALIZAÇÃO DA CARGA HORARIA SOBRE AS AUSENCIAS DOS MEMBROS PARTICIPANTES');
	    $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoDTO->retTodos();
	    $infraAgendamentoDTO->setStrDescricao('Script responsável por atualizar a carga horária dos membros participantes, de acordo com suas ausências.');
	    $infraAgendamentoDTO->setStrComando('MdUtlAgendamentoAutomaticoRN::listarAusenciasRh');
	    $infraAgendamentoDTO->setStrSinAtivo('S');
	    $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
	    $infraAgendamentoDTO->setStrPeriodicidadeComplemento('0,9,15,22');
	    $infraAgendamentoDTO->setStrParametro('mesesPassado=6');
	    $infraAgendamentoDTO->setDthUltimaExecucao(null);
	    $infraAgendamentoDTO->setDthUltimaConclusao(null);
	    $infraAgendamentoDTO->setStrSinSucesso('S');
	    $infraAgendamentoDTO->setStrEmailErro($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));

	    $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        //$infraAgendamentoRN->cadastrar($infraAgendamentoDTO);
        $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);

	    // CRIA AGENDAMENTO SOBRE CHEFIA IMEDIATA DOS USUARIOS
	    $this->logar('SCRIPT AGENDAMENTO ATUALIZAÇÃO REFERENTE AOS MEMBROS NOS TIPOS DE CONTROLES RELACIONADOS A CHEFIA IMEDIATA');
	    $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoDTO->retTodos();
	    $infraAgendamentoDTO->setStrDescricao('Script responsável por atualizar dados dos membros participantes, relacionados à chefia imediata.');
	    $infraAgendamentoDTO->setStrComando('MdUtlAgendamentoAutomaticoRN::listarChefiaImediata');
	    $infraAgendamentoDTO->setStrSinAtivo('S');
	    $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
	    $infraAgendamentoDTO->setStrPeriodicidadeComplemento('10,16,23');
	    $infraAgendamentoDTO->setStrParametro(null);
	    $infraAgendamentoDTO->setDthUltimaExecucao(null);
	    $infraAgendamentoDTO->setDthUltimaConclusao(null);
	    $infraAgendamentoDTO->setStrSinSucesso('S');
	    $infraAgendamentoDTO->setStrEmailErro($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));

	    $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        //$infraAgendamentoRN->cadastrar($infraAgendamentoDTO);
        $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);

	    // CRIA AGENDAMENTO PARA CADASTRAR O PERIODO DOS USUARIOS
	    $this->logar('SCRIPT AGENDAMENTO CADASTRO INICIAL DA CARGA HORARIA DOS MEMBROS PARTICIPANTES');
	    $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoDTO->retTodos();
	    $infraAgendamentoDTO->setStrDescricao('Script responsável por cadastrar os novos períodos e a carga horária dos membros participantes.');
	    $infraAgendamentoDTO->setStrComando('MdUtlAgendamentoAutomaticoRN::incluirPeriodo');
	    $infraAgendamentoDTO->setStrSinAtivo('S');
	    $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
	    $infraAgendamentoDTO->setStrPeriodicidadeComplemento('0');
	    $infraAgendamentoDTO->setStrParametro(null);
	    $infraAgendamentoDTO->setDthUltimaExecucao(null);
	    $infraAgendamentoDTO->setDthUltimaConclusao(null);
	    $infraAgendamentoDTO->setStrSinSucesso('S');
	    $infraAgendamentoDTO->setStrEmailErro($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));

	    $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        //$infraAgendamentoRN->cadastrar($infraAgendamentoDTO);
        $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);

	    $this->logar('CRIA ÍNDICES NAS TABELAS: md_utl_controle_dsmp e md_utl_hist_controle_dsmp SOBRE A COLUNA: dth_atual');
	    InfraDebug::getInstance()->gravar('INICIADO ESTA EXECUÇÃO: ' . InfraData::getStrHoraAtual() );
		    $objInfraMetaBD->criarIndice('md_utl_controle_dsmp', 'i07_md_utl_controle_dsmp', array('dth_atual'));
		    $objInfraMetaBD->criarIndice('md_utl_hist_controle_dsmp', 'i07_md_utl_hist_controle_dsmp', array('dth_atual'));
	    InfraDebug::getInstance()->gravar('FINALIZADO ESTA EXECUÇÃO: ' . InfraData::getStrHoraAtual() );

	    /* EXECUCAO DA COLUNA ID_PROCEDIMENTO PARA NULL */
	    $this->logar('ALTERA O CAMPO id_procedimento nulo NAS TABELAS: md_utl_controle_dsmp e md_utl_hist_controle_dsmp');
	    InfraDebug::getInstance()->gravar('INICIADO ESTA EXECUÇÃO: ' . InfraData::getStrHoraAtual() );

	    $objInfraMetaBD->excluirChaveEstrangeira('md_utl_controle_dsmp', 'fk1_md_utl_controle_dsmp');
	    $objInfraMetaBD->alterarColuna('md_utl_controle_dsmp', 'id_procedimento', $objInfraMetaBD->tipoNumeroGrande(), 'null');

	    $objInfraMetaBD->excluirChaveEstrangeira('md_utl_hist_controle_dsmp', 'fk1_md_utl_hist_controle_dsmp');
	    $objInfraMetaBD->alterarColuna('md_utl_hist_controle_dsmp', 'id_procedimento', $objInfraMetaBD->tipoNumeroGrande(), 'null');

	    InfraDebug::getInstance()->gravar('FINALIZADO ESTA EXECUÇÃO: ' . InfraData::getStrHoraAtual() );
	    /* FIM EXECUCAO DA COLUNA ID_PROCEDIMENTO PARA NULL */

	    // ATUALIZACAO NA INFRA PARAMETRO
	    $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
	    BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'2.1.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

	    $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.1.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    public function populaDadosLegadoDocumento(){
        InfraDebug::getInstance()->setBolDebugInfra(true);

        $numRegistrosPorPag = 2000;
        $numPaginaAtual     = 0;
        $qtd_atualizados    = 0;

        $objAnaliseProdutoBD  = new MdUtlRelAnaliseProdutoBD(BancoSEI::getInstance());
        $objDocumentoBD       = new DocumentoBD(BancoSEI::getInstance());

        //RETORNA QTD TOTAL DE REGISTROS POSSIVEIS DE ATUALIZAÇÃO
        $objAnProdDTO = new MdUtlRelAnaliseProdutoDTO();
        $objAnProdDTO->setDblIdDocumentoScript( '' , InfraDTO::$OPER_DIFERENTE );
        $objAnProdDTO->retNumIdMdUtlRelAnaliseProduto();
        $objAnProdDTO->retDblIdDocumentoScript();
        $objAnProdDTO->retStrProtocoloFormatado();

        $tt_rows = $objAnaliseProdutoBD->contar( $objAnProdDTO );
        $this->logar("QUANTIDADE DE REGISTROS COM ID_DOCUMENTO PREENCHIDO: $tt_rows");
        
        while( true )
        {
            $objAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();

            //retorna os dados que contem o numero do id_documento
            $objAnaliseProdutoDTO->setDblIdDocumentoScript( '' , InfraDTO::$OPER_DIFERENTE );            
            $objAnaliseProdutoDTO->setNumPaginaAtual( $numPaginaAtual );            
            $objAnaliseProdutoDTO->setNumMaxRegistrosRetorno( $numRegistrosPorPag );
            $objAnaliseProdutoDTO->setOrdNumIdMdUtlRelAnaliseProduto( InfraDTO::$TIPO_ORDENACAO_ASC );

            $objAnaliseProdutoDTO->retNumIdMdUtlRelAnaliseProduto();
            $objAnaliseProdutoDTO->retDblIdDocumentoScript();
            $objAnaliseProdutoDTO->retStrProtocoloFormatado();

            $arrAnaliseProdutoObj = $objAnaliseProdutoBD->listar( $objAnaliseProdutoDTO );
            
            InfraDebug::getInstance()->setBolDebugInfra(false);

            if ( !empty( $arrAnaliseProdutoObj ) )
            {
                $arrIdsDocumento = array_unique( 
                    InfraArray::converterArrInfraDTO( $arrAnaliseProdutoObj , 'IdDocumentoScript' ) 
                );

                //retorna o Numero SEI filtrado pelo id_documento    da consulta anterior
                $objDocumentoDTO = new DocumentoDTO();

                $objDocumentoDTO->setDblIdDocumento( $arrIdsDocumento , InfraDTO::$OPER_IN );
                $objDocumentoDTO->retDblIdDocumento();
                $objDocumentoDTO->retStrProtocoloDocumentoFormatado();

                $arrDocumento = InfraArray::converterArrInfraDTO(
                    $objDocumentoBD->listar( $objDocumentoDTO ) , 'ProtocoloDocumentoFormatado' , 'IdDocumento'
                );

                //atualiza a nova coluna[protocolo_formatado] com o Numero SEI consultado anteriormente
                foreach ( $arrAnaliseProdutoObj as $k => $v ) {
                    if ( array_key_exists( $v->getDblIdDocumentoScript() , $arrDocumento ) ){
                        $arrAnaliseProdutoObj[$k]->setStrProtocoloFormatado( $arrDocumento[$v->getDblIdDocumentoScript()] );
                        $objAnaliseProdutoBD->alterar( $arrAnaliseProdutoObj[$k] );
                        $qtd_atualizados++;
                    }
                }
                InfraDebug::getInstance()->setBolDebugInfra(true);
                $numPaginaAtual++;
            }
            else 
            {
                break;
            }
        };
        
        $this->logar("QUANTIDADE DE REGISTROS ATUALIZADOS COM ID_DOCUMENTO PREENCHIDO: $qtd_atualizados");
    }

    protected function atualizarSinRealizarAvalProdProd()
    {

        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;
        $objMdUtlRevisaoBD = new MdUtlRevisaoBD(BancoSEI::getInstance());

        $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
        $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
        $objMdUtlRevisaoDTO->setStrSinRealizarAvalProdProd(null, InfraDTO::$OPER_IGUAL);
        $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
        $totalRegistos = $objMdUtlRevisaoBD->contar($objMdUtlRevisaoDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_revisao: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {

            $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
            $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
            $objMdUtlRevisaoDTO->setStrSinRealizarAvalProdProd(null, InfraDTO::$OPER_IGUAL);
            $objMdUtlRevisaoDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlRevisaoDTO->setNumPaginaAtual($pagina);
            $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();

            $arrObjRevisao = $objMdUtlRevisaoBD->listar($objMdUtlRevisaoDTO);

            foreach ($arrObjRevisao as $objRevisao) {

                $objRevisao->setStrSinRealizarAvalProdProd('S');
                $objMdUtlRevisaoBD->alterar($objRevisao);
            }

            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_revisao " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function replaceRevisaoParaAvaliacao()
    {
        // Realizar replace na coluna 'tipo_acao' de 'Revisão' para 'Avaliação'
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setStrTipoAcao(array('Revisão', 'Contestação de Revisão','Aprovação de Contestatação', 'Reprovação de Contestatação','Retorno de Status'), InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->retStrTipoAcao();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $arrObjMdUtlControleDsmp = $objMdUtlControleDsmpBD->listar($objMdUtlControleDsmpDTO);

        foreach ($arrObjMdUtlControleDsmp as $objMdUtlControleDsmp)
        {
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlControleDsmp($objMdUtlControleDsmp->getNumIdMdUtlControleDsmp());
            $objMdUtlControleDsmpDTO->retStrTipoAcao();
            $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
            $objMdUtlControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);
            switch ($objMdUtlControleDsmp->getStrTipoAcao())
            {
                case 'Revisão':
                    $objMdUtlControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO);
                    break;
                case 'Contestação de Revisão':
                    $objMdUtlControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO);
                    break;
                case 'Aprovação de Contestatação':
                    $objMdUtlControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_CONTESTACAO);
                    break;
                case 'Reprovação de Contestatação':
                    $objMdUtlControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RPV_CONTESTACAO);
                    break;
                case 'Retorno de Status':
                    $objMdUtlControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS);
                    break;
            }

            $objMdUtlControleDsmpBD->alterar($objMdUtlControleDsmp);
        }

        // Realizar replace na coluna 'tipo_acao' de 'Revisão' para 'Avaliação' em historico
        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setStrTipoAcao(array('Revisão', 'Contestação de Revisão','Aprovação de Contestatação', 'Reprovação de Contestatação', 'Retorno de Status'), InfraDTO::$OPER_IN);
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
        $arrObjMdUtlHistControleDsmp = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

        foreach ($arrObjMdUtlHistControleDsmp as $objMdUtlHistControleDsmp)
        {
            $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
            $objMdUtlHistControleDsmpDTO->setNumIdMdUtlHistControleDsmp($objMdUtlHistControleDsmp->getNumIdMdUtlHistControleDsmp());
            $objMdUtlHistControleDsmpDTO->retStrTipoAcao();
            $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
            $objMdUtlHistControleDsmp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);
            switch ($objMdUtlHistControleDsmp->getStrTipoAcao())
            {
                case 'Revisão':
                    $objMdUtlHistControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO);
                    break;
                case 'Contestação de Revisão':
                    $objMdUtlHistControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO);
                    break;
                case 'Aprovação de Contestatação':
                    $objMdUtlHistControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_CONTESTACAO);
                    break;
                case 'Reprovação de Contestatação':
                    $objMdUtlHistControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RPV_CONTESTACAO);
                    break;
                case 'Retorno de Status':
                    $objMdUtlHistControleDsmp->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS);
                    break;
            }
            $objMdUtlHistControleDsmpBD->alterar($objMdUtlHistControleDsmp);
        }
    }

    protected function atualizarTipoAcaoMdUtlHistControleDsmpPercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;
        $objMdUtlProcedimentoBD = new MdUtlProcedimentoBD(BancoSEI::getInstance());
        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());

        $objMdUtlProcedimentoDTO = new MdUtlProcedimentoDTO();
        $objMdUtlProcedimentoDTO->retTodos();
        $objMdUtlProcedimentoDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlProcedimentoBD->contar($objMdUtlProcedimentoDTO);

        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros procedimento: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);

        $pagina = 0;

        while($qtdRegistros < $totalRegistos) {
            $objMdUtlProcedimentoDTO = new MdUtlProcedimentoDTO();
            $objMdUtlProcedimentoDTO->retDblIdProcedimento();
            $objMdUtlProcedimentoDTO->setBolExclusaoLogica(false);
            $objMdUtlProcedimentoDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlProcedimentoDTO->setNumPaginaAtual($pagina);
            $arrObjProcedimento = $objMdUtlProcedimentoBD->listar($objMdUtlProcedimentoDTO);

            foreach ($arrObjProcedimento as $objProcedimento) {

                $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                $objMdUtlControleDsmpDTO->setDblIdProcedimento($objProcedimento->getDblIdProcedimento());
                $objMdUtlControleDsmpDTO->setBolExclusaoLogica(false);
                $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(1);
                $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                $objMdUtlControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);

                if (empty($objMdUtlControleDsmp)) {
                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($objProcedimento->getDblIdProcedimento());
                    $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retStrTipoAcao();
                    $objMdUtlHistControleDsmpDTO->retStrStaAtendimentoDsmp();
                    $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                    $arrMdUtlHistControleDsmp = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

                    if (
                        $arrMdUtlHistControleDsmp && current($arrMdUtlHistControleDsmp)->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM &&
                        $arrMdUtlHistControleDsmp[1]->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO &&
                        ($arrMdUtlHistControleDsmp[1]->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_ANALISE || $arrMdUtlHistControleDsmp[1]->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE)
                    ){
                        $objHistorico = current($arrMdUtlHistControleDsmp);
                        $objHistorico->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM);
                        $objMdUtlHistControleDsmpBD->alterar($objHistorico);
                    }
                }
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_hist_controle_dsmp " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarCamposTriagemNull()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlTriagemBD = new MdUtlTriagemBD(BancoSEI::getInstance());

        $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
        $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
        $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlTriagemBD->contar($objMdUtlTriagemDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_triagem: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {

            $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
            $objMdUtlTriagemDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlTriagemDTO->setNumPaginaAtual($pagina);
            $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
            $objMdUtlTriagemDTO->retNumTempoExecucao();
            $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
            $objMdUtlTriagemDTO->retDthInicio();
            $objMdUtlTriagemDTO->retDthAtual();
            $objMdUtlTriagemDTO->retDthPrazo();
            $objMdUtlTriagemDTO->retNumTempoExecucao();
            $arrObjTriagem = $objMdUtlTriagemBD->listar($objMdUtlTriagemDTO);

            foreach ($arrObjTriagem as $objTriagem) {

                $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                $objMdUtlHistControleDsmpDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
                $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
                $objMdUtlHistControleDsmpDTO->retNumIdMdUtlTriagem();
                $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAdmFila();
                $objMdUtlHistControleDsmpDTO->retDblIdProcedimento();
                $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                $objMdUtlHistControleDsmpDTO->retNumIdUnidade();
                $objMdUtlHistControleDsmpDTO->retStrTipoAcao();
                $objHistorico = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);

                if (!$objHistorico){
                    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                    $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlTriagem();
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFila();
                    $objMdUtlControleDsmpDTO->retDblIdProcedimento();
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                    $objMdUtlControleDsmpDTO->retNumIdUnidade();
                    $objMdUtlControleDsmpDTO->retStrTipoAcao();
                    $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);
                }

                if (($objHistorico || $objControleDsmp ) && (is_null($objTriagem->getDthInicio()) || is_null($objTriagem->getDthPrazo()) || is_null($objTriagem->getNumTempoExecucao()))) {

                    $idProcedimento = $objHistorico ? $objHistorico->getDblIdProcedimento() : $objControleDsmp->getDblIdProcedimento();
                    $idUnidade = $objHistorico ? $objHistorico->getNumIdUnidade() : $objControleDsmp->getNumIdUnidade();

                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
                    $objMdUtlHistControleDsmpDTO->setNumIdUnidade($idUnidade);
                    $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retStrTipoAcao();
                    $objMdUtlHistControleDsmpDTO->retDthAtual();
                    $objMdUtlHistControleDsmpDTO->retDthPrazoTarefa();
                    $objMdUtlHistControleDsmpDTO->retStrDetalhe();
                    $objMdUtlHistControleDsmpDTO->retNumTempoExecucao();
                    $arrObjHistoricoPorProcedimento = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

                    foreach ($arrObjHistoricoPorProcedimento as $objHistoricoPorProcedimento)
                    {
                        // caso tenha varios registros no historico depois da triagem feita
                        if  (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico)
                        {
                            if ($objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO){
                                $params['dataInicio'] = $objHistoricoPorProcedimento->getDthAtual();
                                $params['tempoExecucao'] = $objHistoricoPorProcedimento->getNumTempoExecucao();
                                $params['dataPrazo'] = $objHistoricoPorProcedimento->getDthPrazoTarefa() ? $objHistoricoPorProcedimento->getDthPrazoTarefa() : null ;
                                $idUltimaDistribuicao = $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp();
                            }

                            if (
                                $objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO &&
                                $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() > $idUltimaDistribuicao
                            ){
                                $params['dataPrazo'] = $objHistoricoPorProcedimento->getDthPrazoTarefa() ? $objHistoricoPorProcedimento->getDthPrazoTarefa() : null;
                            }

                        }
                    }
                    // se for uma retriagem atribuir o valor da fila
                    if ($objHistorico && $objHistorico->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM) {
                        $params['tempoExecucao'] = $this->recuperarTempoExecucaoTriagemFila($objHistorico->getNumIdMdUtlAdmFila());
                    }
                    if (!$objHistorico && $objControleDsmp->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM) {
                        $params['tempoExecucao'] = $this->recuperarTempoExecucaoTriagemFila($objControleDsmp->getNumIdMdUtlAdmFila());
                    }

                    // verificar se é resultante de uma retriagem retornada ao responsável

//                    1 DESCOBRIR SE O ANTERIOR É UMA DISTRIBUIÇÃO
//                    2 DESCOBRIR SE O ANTERIOR À DISTRIBUIÇÃO É UMA REVISÃO
//                    3 SE ESSA REVISÃO FOR RETORNAR PARA O RESPONSAVEL

                    foreach ($arrObjHistoricoPorProcedimento as $objHistoricoPorProcedimento) {
                        if (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico){
                            $objetoHistoricoRetriagemAnterior = $objHistoricoPorProcedimento;
                        }
                    }

                    // caso seja uma distribuição .... procura se o outro registro é revisao
                    if ($objetoHistoricoRetriagemAnterior && $objetoHistoricoRetriagemAnterior->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO) {
                        foreach ($arrObjHistoricoPorProcedimento as $objHistoricoPorProcedimento) {
                            if ($objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objetoHistoricoRetriagemAnterior->getNumIdMdUtlHistControleDsmp()){
                                $objetoHistoricoDistribuicaoAnterior = $objHistoricoPorProcedimento;
                            }
                        }
                    }
                    // DESCOBRIR SE O ANTERIOR É UMA DISTRIBUIÇÃO
                    // SE ESSA REVISÃO FOR RETORNAR PARA O RESPONSAVEL
                    if (
                        $objetoHistoricoDistribuicaoAnterior &&
                        $objetoHistoricoDistribuicaoAnterior->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO &&
                        $objetoHistoricoDistribuicaoAnterior->getStrDetalhe() == MdUtlRevisaoRN::$STR_VOLTAR_PARA_RESPONSAVEL
                    ) {
                        $params['tempoExecucao'] = 0;
                    }

                    if (is_null($objTriagem->getDthInicio())) {
                        $objTriagem->setDthInicio($params['dataInicio']);
                    }

                    if (is_null($objTriagem->getDthPrazo())) {
                        $objTriagem->setDthPrazo($params['dataPrazo']);
                    }

                    if (is_null($objTriagem->getNumTempoExecucao())) {
                        $objTriagem->setNumTempoExecucao($params['tempoExecucao']);
                    }

                    $objMdUtlTriagemBD->alterar($objTriagem);

                    // limpa as variaveis para outra verificação
                    unset($objetoHistoricoRetriagemAnterior);
                    unset($objetoHistoricoDistribuicaoAnterior);
                }
            }

            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_triagem " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function recuperarTempoExecucaoTriagemFila($idFila) {
        //pega o tempo de execucao da fila
        $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
        $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
        $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlAdmFilaDTO->retNumTmpExecucaoTriagem();
        $objMdUtlAdmFila = $objMdUtlAdmFilaRN->consultar($objMdUtlAdmFilaDTO);

        return $objMdUtlAdmFila->getNumTmpExecucaoTriagem();
    }

    protected function atualizarCamposAnaliseTempoExecucaoNull(){
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAnaliseBD = new MdUtlAnaliseBD(BancoSEI::getInstance());

        $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
        $objMdUtlAnaliseDTO->setBolExclusaoLogica(false);
        $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
        $totalRegistos = $objMdUtlAnaliseBD->contar($objMdUtlAnaliseDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_analise: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {

            $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
            $objMdUtlAnaliseDTO->setBolExclusaoLogica(false);
            $objMdUtlAnaliseDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlAnaliseDTO->setNumPaginaAtual($pagina);
            $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
            $objMdUtlAnaliseDTO->retDthInicio();
            $objMdUtlAnaliseDTO->retNumTempoExecucao();
            $objMdUtlAnaliseDTO->retDthPrazo();
            $arrObjAnalise = $objMdUtlAnaliseBD->listar($objMdUtlAnaliseDTO);

            foreach ($arrObjAnalise as $objAnalise) {

                if ($objAnalise && (is_null($objAnalise->getNumTempoExecucao()) || is_null($objAnalise->getDthInicio()) || is_null($objAnalise->getDthPrazo()))) {

                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                    $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAnalise($objAnalise->getNumIdMdUtlAnalise());
                    $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                    $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
                    $objMdUtlHistControleDsmpDTO->retDblIdProcedimento();
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retNumIdUnidade();
                    $objHistorico = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);

                    if (!$objHistorico){
                        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                        $objMdUtlControleDsmpDTO->setNumIdMdUtlAnalise($objAnalise->getNumIdMdUtlAnalise());
                        $objMdUtlControleDsmpDTO->retNumIdMdUtlTriagem();
                        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFila();
                        $objMdUtlControleDsmpDTO->retDblIdProcedimento();
                        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                        $objMdUtlControleDsmpDTO->retNumIdUnidade();
                        $objMdUtlControleDsmpDTO->retStrTipoAcao();
                        $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);
                    }

                    if (($objHistorico || $objControleDsmp) && (is_null($objAnalise->getNumTempoExecucao()) || is_null($objAnalise->getDthInicio()) || is_null($objAnalise->getDthPrazo()))) {

                        $idProcedimento = $objHistorico ? $objHistorico->getDblIdProcedimento() : $objControleDsmp->getDblIdProcedimento();
                        $idUnidade = $objHistorico ? $objHistorico->getNumIdUnidade() : $objControleDsmp->getNumIdUnidade();

                        $objMdUtlHistControleDsmpPorProcedimentoDTO = new MdUtlHistControleDsmpDTO();
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->setDblIdProcedimento($idProcedimento);
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->setNumIdUnidade($idUnidade);
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->setBolExclusaoLogica(false);
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->retNumIdMdUtlHistControleDsmp();
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->retNumTempoExecucao();
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->retStrTipoAcao();
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->retDthAtual();
                        $objMdUtlHistControleDsmpPorProcedimentoDTO->retDthPrazoTarefa();
                        $arrObjHistoricoPorProcedimento = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpPorProcedimentoDTO);

                        foreach ($arrObjHistoricoPorProcedimento as $objHistoricoPorProcedimento) {
                            if (
                                (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico) &&
                                $objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO
                            ) {
                                $tempoExecucao = $objHistoricoPorProcedimento->getNumTempoExecucao();
                                $dthInicio = $objHistoricoPorProcedimento->getDthAtual();
                                $dthPrazoTarefa = $objHistoricoPorProcedimento->getDthPrazoTarefa() ? $objHistoricoPorProcedimento->getDthPrazoTarefa() : null;
                            }
                        }

                        if (is_null($objAnalise->getNumTempoExecucao())) {
                            $objAnalise->setNumTempoExecucao($tempoExecucao);
                        }

                        if (is_null($objAnalise->getDthInicio())) {
                            $objAnalise->setDthInicio($dthInicio);
                        }

                        if (is_null($objAnalise->getDthPrazo())) {
                            $objAnalise->setDthPrazo($dthPrazoTarefa);
                        }

                        $objMdUtlAnaliseBD->alterar($objAnalise);
                    }
                }
            }

            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_analise " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarCamposRevisaoTempoExecucaoNull(){
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlRevisaoBD = new MdUtlRevisaoBD(BancoSEI::getInstance());

        $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
        $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
        $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
        $totalRegistos = $objMdUtlRevisaoBD->contar($objMdUtlRevisaoDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_revisao: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {

            $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
            $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
            $objMdUtlRevisaoDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlRevisaoDTO->setNumPaginaAtual($pagina);
            $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
            $objMdUtlRevisaoDTO->retDthInicio();
            $objMdUtlRevisaoDTO->retNumTempoExecucao();
            $objMdUtlRevisaoDTO->retDthAtual();
            $objMdUtlRevisaoDTO->retDthPrazo();
            $arrObjRevisao = $objMdUtlRevisaoBD->listar($objMdUtlRevisaoDTO);

            foreach ($arrObjRevisao as $objRevisao) {

                $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                $objMdUtlHistControleDsmpDTO->setNumIdMdUtlRevisao($objRevisao->getNumIdMdUtlRevisao());
                $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
                $objMdUtlHistControleDsmpDTO->retDblIdProcedimento();
                $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                $objMdUtlHistControleDsmpDTO->retNumIdUnidade();
                $objHistorico = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);

                if (!$objHistorico){
                    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                    $objMdUtlControleDsmpDTO->setNumIdMdUtlRevisao($objRevisao->getNumIdMdUtlRevisao());
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlTriagem();
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFila();
                    $objMdUtlControleDsmpDTO->retDblIdProcedimento();
                    $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                    $objMdUtlControleDsmpDTO->retNumIdUnidade();
                    $objMdUtlControleDsmpDTO->retStrTipoAcao();
                    $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);
                }

                if (($objHistorico || $objControleDsmp) && (is_null($objRevisao->getNumTempoExecucao()) || is_null($objRevisao->getDthAtual()) || is_null($objRevisao->getDthPrazo()))) {

                    $idProcedimento = $objHistorico ? $objHistorico->getDblIdProcedimento() : $objControleDsmp->getDblIdProcedimento();
                    $idUnidade = $objHistorico ? $objHistorico->getNumIdUnidade() : $objControleDsmp->getNumIdUnidade();

                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
                    $objMdUtlHistControleDsmpDTO->setNumIdUnidade($idUnidade);
                    $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                    $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retNumTempoExecucao();
                    $objMdUtlHistControleDsmpDTO->retStrTipoAcao();
                    $objMdUtlHistControleDsmpDTO->retDthAtual();
                    $objMdUtlHistControleDsmpDTO->retDthPrazoTarefa();
                    $objMdUtlHistControleDsmpDTO->retStrDetalhe();
                    $arrObjHistoricoPorProcedimento = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

                    foreach ($arrObjHistoricoPorProcedimento as $objHistoricoPorProcedimento)
                    {
                        if  (
                            (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico) &&
                            $objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO
                        )
                        {
                            $tempoExecucao = $objHistoricoPorProcedimento->getNumTempoExecucao();
                            $dataInicio = $objHistoricoPorProcedimento->getDthAtual();
                            $dataPrazo = $objHistoricoPorProcedimento->getDthPrazoTarefa() ? $objHistoricoPorProcedimento->getDthPrazoTarefa() : null;

                            $idUltimaDistribuicao = $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp();
                        }


                        //A data inicio é a data da solicitação da contestação aprovada
                        // A data prazo de uma revisão de uma contestação é nula
                        if (
                            $idUltimaDistribuicao &&
                            $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() > $idUltimaDistribuicao &&
                            (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() < $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico) &&
                            $objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO &&
                            $objHistoricoPorProcedimento->getStrDetalhe() == MdUtlContestacaoRN::$STR_SOLICITACAO
                        ){
                            $dataSolicitacao = $objHistoricoPorProcedimento->getDthAtual();
                        }

                        if (
                            $dataSolicitacao &&
                            (($objHistorico && $objHistoricoPorProcedimento->getNumIdMdUtlHistControleDsmp() == $objHistorico->getNumIdMdUtlHistControleDsmp()) || !$objHistorico) &&
                            $objHistoricoPorProcedimento->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO &&
                            ($objHistoricoPorProcedimento->getStrDetalhe() == MdUtlAjustePrazoRN::$STR_APROVADA ||
                                $objHistoricoPorProcedimento->getStrDetalhe() == MdUtlControleDsmpRN::$STR_FLUXO_FINALIZADO)
                        ){
                            $dataInicio = $dataSolicitacao;
                            $tempoExecucao = 0;
                            $dataPrazo = null;
                        }
                    }

                    if(is_null($objRevisao->getNumTempoExecucao())){
                        $objRevisao->setNumTempoExecucao($tempoExecucao);
                    }

                    if(is_null($objRevisao->getDthInicio())){
                        $objRevisao->setDthInicio($dataInicio);
                    }

                    if(is_null($objRevisao->getDthPrazo())){
                        $objRevisao->setDthPrazo($dataPrazo);
                    }

                    $objMdUtlRevisaoBD->alterar($objRevisao);
                }
            }

            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_revisao " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarMdUtlTriagemPercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;
        $objMdUtlTriagemBD = new MdUtlTriagemBD(BancoSEI::getInstance());
        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAdmHistPrmGrUsuBD = new MdUtlAdmHistPrmGrUsuBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());

        $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
        $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
        $objMdUtlTriagemDTO->setStrStaTipoPresenca(null);
        $objMdUtlTriagemDTO->setNumTempoExecucaoAtribuido(null);
        $objMdUtlTriagemDTO->setNumPercentualDesempenho(null);
        $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlTriagemBD->contar($objMdUtlTriagemDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_triagem: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {
            $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
            $objMdUtlTriagemDTO->setStrStaTipoPresenca(null);
            $objMdUtlTriagemDTO->setNumTempoExecucaoAtribuido(null);
            $objMdUtlTriagemDTO->setNumPercentualDesempenho(null);
            $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
            $objMdUtlTriagemDTO->retNumIdUsuario();
            $objMdUtlTriagemDTO->retDthAtual();
            $objMdUtlTriagemDTO->retNumTempoExecucao();
            $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
            $objMdUtlTriagemDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $arrObjTriagem = $objMdUtlTriagemBD->listar($objMdUtlTriagemDTO);

            foreach ($arrObjTriagem as $objTriagem) {

                $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
                $objMdUtlControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM, MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM), InfraDTO::$OPER_IN);
                $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);

                if (!$objControleDsmp) {
                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
                    $objMdUtlHistControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM, MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM), InfraDTO::$OPER_IN);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                    $objControleDsmp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);
                }

                $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($objControleDsmp->getNumIdMdUtlAdmTpCtrlDesemp());
                $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
                $objMdUtlAdmTpCtrlDesemp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

                $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
                $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuario($objTriagem->getNumIdUsuario());
                $objMdUtlAdmHistPrmGrUsuDTO->retNumFatorDesempDiferenciado();
                $objMdUtlAdmHistPrmGrUsuDTO->retStrStaTipoPresenca();
                $objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
                $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
                $arrMdUtlAdmHistPrmGrUsu = $objMdUtlAdmHistPrmGrUsuBD->listar($objMdUtlAdmHistPrmGrUsuDTO);
                $arrHistPrmGrUsu = $this->buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objTriagem);

                switch ($arrHistPrmGrUsu['tipoPresenca']) {
                    case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO :
                        $percentualDesempenho = $arrHistPrmGrUsu['fatorDesempenhoDiferenciado'];
                        break;
                    case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                        $percentualDesempenho = $this->retornarPercentualTeletrabalho($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                        break;
                    default:
                        $percentualDesempenho = 0;
                }

                $tempoExecucao = intval($objTriagem->getNumTempoExecucao() / (1 + ($percentualDesempenho / 100)));

                // Populando tipo de presença, percentual de desempenho e tempo atribuído
                $objTriagem->setStrStaTipoPresenca($arrHistPrmGrUsu['tipoPresenca']);
                $objTriagem->setNumPercentualDesempenho($percentualDesempenho);
                $objTriagem->setNumTempoExecucaoAtribuido($tempoExecucao);

                $objMdUtlTriagemBD->alterar($objTriagem);
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_triagem " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objTabela)
    {
        $arrRetorno = [];

        foreach ($arrMdUtlAdmHistPrmGrUsu as $objMdUtlAdmHistPrmGrUsu) {
            $dataAtual = DateTime::createFromFormat('d/m/Y H:i:s', $objTabela->getDthAtual());
            $dataInicial = DateTime::createFromFormat('d/m/Y H:i:s', $objMdUtlAdmHistPrmGrUsu->getDthInicial());
            $dataFinal = $objMdUtlAdmHistPrmGrUsu->getDthFinal() ? DateTime::createFromFormat('d/m/Y H:i:s', $objMdUtlAdmHistPrmGrUsu->getDthFinal()) : $objMdUtlAdmHistPrmGrUsu->getDthFinal();

            if (!empty($dataFinal) && (
                    $dataAtual >= $dataInicial &&
                    $dataAtual < $dataFinal)
            ) {
                $arrRetorno['tipoPresenca'] = $objMdUtlAdmHistPrmGrUsu->getStrStaTipoPresenca();
                $arrRetorno['fatorDesempenhoDiferenciado'] = $objMdUtlAdmHistPrmGrUsu->getNumFatorDesempDiferenciado();
            }
            if (
                $dataAtual >= $dataInicial &&
                empty($dataFinal)
            ) {
                $arrRetorno['tipoPresenca'] = $objMdUtlAdmHistPrmGrUsu->getStrStaTipoPresenca();
                $arrRetorno['fatorDesempenhoDiferenciado'] = $objMdUtlAdmHistPrmGrUsu->getNumFatorDesempDiferenciado();
            }
        }

        return $arrRetorno;
    }

    protected function retornarPercentualTeletrabalho($idMdUtlAdmPrmGr)
    {
        $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD(BancoSEI::getInstance());
        $objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
        $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
        $objMdUtlAdmPrmGrDTO->retDblPercentualTeletrabalho();
        $objMdUtlAdmPrmGr = $objMdUtlAdmPrmGrBD->consultar($objMdUtlAdmPrmGrDTO);

        return $objMdUtlAdmPrmGr->getDblPercentualTeletrabalho();
    }

    protected function atualizarMdUtlAnalisePercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlAnaliseBD = new MdUtlAnaliseBD(BancoSEI::getInstance());
        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAdmHistPrmGrUsuBD = new MdUtlAdmHistPrmGrUsuBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());

        $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
        $objMdUtlAnaliseDTO->setStrStaTipoPresenca(null);
        $objMdUtlAnaliseDTO->setNumTempoExecucaoAtribuido(null);
        $objMdUtlAnaliseDTO->setNumPercentualDesempenho(null);
        $objMdUtlAnaliseDTO->setBolExclusaoLogica(false);
        $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
        $totalRegistos = $objMdUtlAnaliseBD->contar($objMdUtlAnaliseDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_analise: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {

            $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
            $objMdUtlAnaliseDTO->setBolExclusaoLogica(false);
            $objMdUtlAnaliseDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlAnaliseDTO->setStrStaTipoPresenca(null);
            $objMdUtlAnaliseDTO->setNumTempoExecucaoAtribuido(null);
            $objMdUtlAnaliseDTO->setNumPercentualDesempenho(null);
            $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
            $objMdUtlAnaliseDTO->retDthAtual();
            $objMdUtlAnaliseDTO->retNumIdUsuario();
            $objMdUtlAnaliseDTO->retNumTempoExecucao();
            $arrObjAnalise = $objMdUtlAnaliseBD->listar($objMdUtlAnaliseDTO);

            foreach ($arrObjAnalise as $objAnalise) {

                $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                $objMdUtlControleDsmpDTO->setNumIdMdUtlAnalise($objAnalise->getNumIdMdUtlAnalise());
                $objMdUtlControleDsmpDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);
                $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);

                if (!$objControleDsmp) {
                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAnalise($objAnalise->getNumIdMdUtlAnalise());
                    $objMdUtlHistControleDsmpDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                    $objControleDsmp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);
                }

                if($objControleDsmp) {

                    $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($objControleDsmp->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
                    $objMdUtlAdmTpCtrlDesemp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

                    $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuario($objAnalise->getNumIdUsuario());
                    $objMdUtlAdmHistPrmGrUsuDTO->retNumFatorDesempDiferenciado();
                    $objMdUtlAdmHistPrmGrUsuDTO->retStrStaTipoPresenca();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
                    $arrMdUtlAdmHistPrmGrUsu = $objMdUtlAdmHistPrmGrUsuBD->listar($objMdUtlAdmHistPrmGrUsuDTO);
                    $arrHistPrmGrUsu = $this->buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objAnalise);

                    switch ($arrHistPrmGrUsu['tipoPresenca']) {
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO :
                            $percentualDesempenho = $arrHistPrmGrUsu['fatorDesempenhoDiferenciado'];
                            break;
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                            $percentualDesempenho = $this->retornarPercentualTeletrabalho($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                            break;
                        default:
                            $percentualDesempenho = 0;
                    }

                    $tempoExecucao = intval($objAnalise->getNumTempoExecucao() / (1 + ($percentualDesempenho / 100)));

                    // Populando tipo de presença, percentual de desempenho e tempo atribuído
                    $objAnalise->setStrStaTipoPresenca($arrHistPrmGrUsu['tipoPresenca']);
                    $objAnalise->setNumPercentualDesempenho($percentualDesempenho);
                    $objAnalise->setNumTempoExecucaoAtribuido($tempoExecucao);

                    $objMdUtlAnaliseBD->alterar($objAnalise);
                }
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_analise " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarMdUtlRevisaoPercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlRevisaoBD = new MdUtlRevisaoBD(BancoSEI::getInstance());
        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAdmHistPrmGrUsuBD = new MdUtlAdmHistPrmGrUsuBD(BancoSEI::getInstance());

        $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
        $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
        $objMdUtlRevisaoDTO->setStrStaTipoPresenca(null);
        $objMdUtlRevisaoDTO->setNumTempoExecucaoAtribuido(null);
        $objMdUtlRevisaoDTO->setNumPercentualDesempenho(null);
        $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlRevisaoBD->contar($objMdUtlRevisaoDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_revisao: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {
            $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
            $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
            $objMdUtlRevisaoDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlRevisaoDTO->setStrStaTipoPresenca(null);
            $objMdUtlRevisaoDTO->setNumTempoExecucaoAtribuido(null);
            $objMdUtlRevisaoDTO->setNumPercentualDesempenho(null);
            $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
            $objMdUtlRevisaoDTO->retDthAtual();
            $objMdUtlRevisaoDTO->retNumIdUsuario();
            $objMdUtlRevisaoDTO->retNumTempoExecucao();
            $arrObjRevisao = $objMdUtlRevisaoBD->listar($objMdUtlRevisaoDTO);

            foreach ($arrObjRevisao as $objRevisao) {

                $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                $objMdUtlControleDsmpDTO->setNumIdMdUtlRevisao($objRevisao->getNumIdMdUtlRevisao());
                $objMdUtlControleDsmpDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);
                $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
                $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);

                if (!$objControleDsmp) {
                    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHistControleDsmpDTO->setNumIdMdUtlRevisao($objRevisao->getNumIdMdUtlRevisao());
                    $objMdUtlHistControleDsmpDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
                    $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                    $objControleDsmp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);
                }

                if($objControleDsmp) {

                    $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($objControleDsmp->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
                    $objMdUtlAdmTpCtrlDesemp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

                    $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuario($objRevisao->getNumIdUsuario());
                    $objMdUtlAdmHistPrmGrUsuDTO->retNumFatorDesempDiferenciado();
                    $objMdUtlAdmHistPrmGrUsuDTO->retStrStaTipoPresenca();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
                    $arrMdUtlAdmHistPrmGrUsu = $objMdUtlAdmHistPrmGrUsuBD->listar($objMdUtlAdmHistPrmGrUsuDTO);
                    $arrHistPrmGrUsu = $this->buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objRevisao);

                    switch ($arrHistPrmGrUsu['tipoPresenca']) {
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO :
                            $percentualDesempenho = $arrHistPrmGrUsu['fatorDesempenhoDiferenciado'];
                            break;
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                            $percentualDesempenho = $this->retornarPercentualTeletrabalho($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                            break;
                        default:
                            $percentualDesempenho = 0;
                    }

                    $tempoExecucao = intval($objRevisao->getNumTempoExecucao() / (1 + ($percentualDesempenho / 100)));

                    // Populando tipo de presença, percentual de desempenho e tempo atribuído
                    $objRevisao->setStrStaTipoPresenca($arrHistPrmGrUsu['tipoPresenca']);
                    $objRevisao->setNumPercentualDesempenho($percentualDesempenho);
                    $objRevisao->setNumTempoExecucaoAtribuido($tempoExecucao);


                    $objMdUtlRevisaoBD->alterar($objRevisao);
                }
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_revisao " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarMdUtlHistControleDsmpPercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAdmHistPrmGrUsuBD = new MdUtlAdmHistPrmGrUsuBD(BancoSEI::getInstance());

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
        $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlHistControleDsmpBD->contar($objMdUtlHistControleDsmpDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_hist_controle_dsmp: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {
            $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
            $objMdUtlHistControleDsmpDTO->retNumIdMdUtlHistControleDsmp();
            $objMdUtlHistControleDsmpDTO->retStrStaAtendimentoDsmp();
            $objMdUtlHistControleDsmpDTO->retNumIdUsuarioDistribuicao();
            $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
            $objMdUtlHistControleDsmpDTO->retNumTempoExecucao();
            $objMdUtlHistControleDsmpDTO->retDthAtual();
            $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
            $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlHistControleDsmpDTO->setNumPaginaAtual($pagina);
            $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);
            $arrObjHistorico = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

            $arrNaoAtualizar = [MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO];
            $arrNaoAtualizarTempoExecucaoAtribuido = [MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE];

            foreach ($arrObjHistorico as $objHistorico) {

                if ($objHistorico && !in_array($objHistorico->getStrStaAtendimentoDsmp(), $arrNaoAtualizar) && $objHistorico->getNumIdUsuarioDistribuicao() != null) {

                    $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($objHistorico->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
                    $objMdUtlAdmTpCtrlDesemp = $objMdUtlHistControleDsmpBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

                    $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuario($objHistorico->getNumIdUsuarioDistribuicao());
                    $objMdUtlAdmHistPrmGrUsuDTO->retNumFatorDesempDiferenciado();
                    $objMdUtlAdmHistPrmGrUsuDTO->retStrStaTipoPresenca();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
                    $arrMdUtlAdmHistPrmGrUsu = $objMdUtlAdmHistPrmGrUsuBD->listar($objMdUtlAdmHistPrmGrUsuDTO);
                    $arrHistPrmGrUsu = $this->buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objHistorico);

                    switch ($arrHistPrmGrUsu['tipoPresenca']) {
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO :
                            $percentualDesempenho = $arrHistPrmGrUsu['fatorDesempenhoDiferenciado'];
                            break;
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                            $percentualDesempenho = $this->retornarPercentualTeletrabalho($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                            break;
                        default:
                            $percentualDesempenho = 0;
                    }

                    $tempoExecucao = intval($objHistorico->getNumTempoExecucao() / (1 + ($percentualDesempenho / 100)));

                    // Populando tipo de presença, percentual de desempenho e tempo atribuído
                    $objHistorico->setStrStaTipoPresenca($arrHistPrmGrUsu['tipoPresenca']);
                    $objHistorico->setNumPercentualDesempenho($percentualDesempenho);

                    //Campo não preenchido quando o Status for igual a Aguardando Triagem, Aguardando Análise, Aguardando Revisão, Aguardando Correção de Triagem ou Aguardando Correção de Análise
                    if (!in_array($objHistorico->getStrStaAtendimentoDsmp(), $arrNaoAtualizarTempoExecucaoAtribuido)) {
                        $objHistorico->setNumTempoExecucaoAtribuido($tempoExecucao);
                    }
                    $objMdUtlHistControleDsmpBD->alterar($objHistorico);
                }
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_hist_controle_dsmp " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    protected function atualizarMdUtlControleDsmpPercentualDesempenho()
    {
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros = 0;

        $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD(BancoSEI::getInstance());
        $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD(BancoSEI::getInstance());
        $objMdUtlAdmHistPrmGrUsuBD = new MdUtlAdmHistPrmGrUsuBD(BancoSEI::getInstance());

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->retStrStaAtendimentoDsmp();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retNumTempoExecucao();
        $objMdUtlControleDsmpDTO->retDthAtual();
        $objMdUtlControleDsmpDTO->setBolExclusaoLogica(false);
        $totalRegistos = $objMdUtlControleDsmpBD->contar($objMdUtlControleDsmpDTO);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        $this->logar("Total de Registros md_utl_controle_dsmp: " . $totalRegistos);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        $pagina = 0;
        while($qtdRegistros < $totalRegistos) {
            $arrObjControleDsmp = $objMdUtlControleDsmpBD->listar($objMdUtlControleDsmpDTO);

            $arrNaoAtualizar = [MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO];
            $arrNaoAtualizarTempoExecucaoAtribuido = [MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE];

            foreach ($arrObjControleDsmp as $objControleDsmp) {

                if ($objControleDsmp && !in_array($objControleDsmp->getStrStaAtendimentoDsmp(), $arrNaoAtualizar) && $objControleDsmp->getNumIdUsuarioDistribuicao() != null) {

                    $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($objControleDsmp->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
                    $objMdUtlAdmTpCtrlDesemp = $objMdUtlAdmTpCtrlDesempBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

                    $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                    $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuario($objControleDsmp->getNumIdUsuarioDistribuicao());
                    $objMdUtlAdmHistPrmGrUsuDTO->retNumFatorDesempDiferenciado();
                    $objMdUtlAdmHistPrmGrUsuDTO->retStrStaTipoPresenca();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
                    $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
                    $arrMdUtlAdmHistPrmGrUsu = $objMdUtlAdmHistPrmGrUsuBD->listar($objMdUtlAdmHistPrmGrUsuDTO);
                    $arrHistPrmGrUsu = $this->buscarHistPrmGrUsu($arrMdUtlAdmHistPrmGrUsu, $objControleDsmp);

                    switch ($arrHistPrmGrUsu['tipoPresenca']) {
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO :
                            $percentualDesempenho = $arrHistPrmGrUsu['fatorDesempenhoDiferenciado'];
                            break;
                        case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                            $percentualDesempenho = $this->retornarPercentualTeletrabalho($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
                            break;
                        default:
                            $percentualDesempenho = 0;
                    }

                    $tempoExecucao = intval($objControleDsmp->getNumTempoExecucao() / (1 + ($percentualDesempenho / 100)));

                    // Populando tipo de presença, percentual de desempenho e tempo atribuído
                    $objControleDsmp->setStrStaTipoPresenca($arrHistPrmGrUsu['tipoPresenca']);
                    $objControleDsmp->setNumPercentualDesempenho($percentualDesempenho);

                    //Campo não preenchido quando o Status for igual a Aguardando Triagem, Aguardando Análise, Aguardando Revisão, Aguardando Correção de Triagem ou Aguardando Correção de Análise
                    if (!in_array($objControleDsmp->getStrStaAtendimentoDsmp(), $arrNaoAtualizarTempoExecucaoAtribuido)) {
                        $objControleDsmp->setNumTempoExecucaoAtribuido($tempoExecucao);

                        //Para o Status Em Correção de Análise, onde ocorreu o Retorno ao Responsável, deve ser atribuído o valor igual a 0
                        if (MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE == $objControleDsmp->getStrStaAtendimentoDsmp()) {
                            $objControleDsmp->setNumTempoExecucaoAtribuido(0);
                        }
                    }
                    $objMdUtlControleDsmpBD->alterar($objControleDsmp);
                }
            }
            $pagina++;
            $qtdRegistros += $qtdRegistrosPorVez;
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $this->logar("ALTERANDO md_utl_controle_dsmp " . $qtdRegistros . " de " . $totalRegistos);
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }
    }

    private function atualizarTempoAtribuidoAtv()
    {
        $qtdRegistrosPorVez = 1000;
        $qtdRegistros       = 0;

        $ojbMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD(BancoSEI::getInstance());
        $objMdUtlControleDsmpBD  = new MdUtlControleDsmpBD(BancoSEI::getInstance());

        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvDTO->setNumTempoExecucaoAtribuido(null);
        $objMdUtlRelTriagemAtvDTO->setBolExclusaoLogica(false);

        $totalRegistos = $ojbMdUtlRelTriagemAtvBD->contar($objMdUtlRelTriagemAtvDTO);

        while($qtdRegistros < $totalRegistos) {

            $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
            $objMdUtlRelTriagemAtvDTO->setNumTempoExecucaoAtribuido(null);
            $objMdUtlRelTriagemAtvDTO->setBolExclusaoLogica(false);
            $objMdUtlRelTriagemAtvDTO->setNumMaxRegistrosRetorno($qtdRegistrosPorVez);
            $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlRelTriagemAtv();
            $objMdUtlRelTriagemAtvDTO->retNumTempoExecucao();
            $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlTriagem();
            #$totalRegistosPorConsulta = $ojbMdUtlRelTriagemAtvBD->contar($objMdUtlRelTriagemAtvDTO);

            $arrMdUtlRelTriagemAtv    = $ojbMdUtlRelTriagemAtvBD->listar($objMdUtlRelTriagemAtvDTO);
            $totalRegistosPorConsulta = !empty( $arrMdUtlRelTriagemAtv ) ? count( $arrMdUtlRelTriagemAtv ) : 0;

            InfraDebug::getInstance()->setBolDebugInfra(false);

            foreach ($arrMdUtlRelTriagemAtv as $objMdUtlRelTriagemAtv){

                $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                $objMdUtlHistControleDsmpDTO->setNumIdMdUtlTriagem($objMdUtlRelTriagemAtv->getNumIdMdUtlTriagem());
                $objMdUtlHistControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM, MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO), InfraDTO::$OPER_IN);
                $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);
                $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
                $objMdUtlHistControleDsmpDTO->setBolExclusaoLogica(false);
                $objMdUtlHistControleDsmpDTO->retNumPercentualDesempenho();
                $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);

                if (!$objControleDsmp) {
                    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                    $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($objMdUtlRelTriagemAtv->getNumIdMdUtlTriagem());
                    $objMdUtlControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM, MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO), InfraDTO::$OPER_IN);
                    $objMdUtlControleDsmpDTO->setBolExclusaoLogica(false);
                    $objMdUtlControleDsmpDTO->retNumPercentualDesempenho();
                    $objControleDsmp = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);
                }

                $tempoExecucao = $objMdUtlRelTriagemAtv->getNumTempoExecucao();
                $objMdUtlRelTriagemAtv->setNumTempoExecucaoAtribuido($tempoExecucao);

                // faz o cálculo e popula registro caso tenha sido distribuido para análise
                if ($objControleDsmp) {
                    $percentualDesenpenho = $objControleDsmp->getNumPercentualDesempenho();
                    $tempoAtribuido = intval($tempoExecucao / (1 + ($percentualDesenpenho / 100)));
                    $objMdUtlRelTriagemAtv->setNumTempoExecucaoAtribuido($tempoAtribuido);
                }
                
                $ojbMdUtlRelTriagemAtvBD->alterar($objMdUtlRelTriagemAtv);
            }
            InfraDebug::getInstance()->setBolDebugInfra(true);
            $qtdRegistros += $totalRegistosPorConsulta;
        }
    }

    private function _cadastrarNovoAgendamento($strDescricao = null, $strComando = null, $strPeriodicidadeComplemento = 0, $strEmailErro = null, $strPeriodicidade = null)
    {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strEmailErro = $objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR');

        $msgLogar = 'Inserção de Novo Agendamento: ' . $strDescricao;
        $this->logar($msgLogar);

        if (is_null($strPeriodicidade)) {
            $strPeriodicidade = InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA;
        }

        if (!is_null($strDescricao) && !is_null($strComando)) {

            $strComando = trim($strComando);

            $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
            $infraAgendamentoDTO->retTodos();
            $infraAgendamentoDTO->setStrDescricao($strDescricao);
            $infraAgendamentoDTO->setStrComando($strComando);

            $infraAgendamentoDTO->setStrSinAtivo('S');
            $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao($strPeriodicidade);
            $infraAgendamentoDTO->setStrPeriodicidadeComplemento($strPeriodicidadeComplemento);
            $infraAgendamentoDTO->setStrParametro(null);
            $infraAgendamentoDTO->setDthUltimaExecucao(null);
            $infraAgendamentoDTO->setDthUltimaConclusao(null);
            $infraAgendamentoDTO->setStrSinSucesso('S');
            $infraAgendamentoDTO->setStrEmailErro($strEmailErro);

            $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
            $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);
        }
    }

    private function _atualizarHistControleDsmp()
    {
        $msgLogar = 'Atualização do Histórico do Controle de Desempenho: ';
        $this->logar($msgLogar);
        $objInfraBanco = BancoSEI::getInstance();

        $utlRegrasGeraisRN = new MdUtlRegrasGeraisRN();

        $sql = 'SELECT DISTINCT p.id_procedimento FROM procedimento p
                INNER JOIN md_utl_hist_controle_dsmp muhcd ON p.id_procedimento = muhcd.id_procedimento
                ORDER BY p.id_procedimento ASC';

        $arrProcedimentos = $objInfraBanco->consultarSql($sql);
        if (!empty($arrProcedimentos)) {
            foreach ($arrProcedimentos as $procedimento) {
                $idProcedimento = $procedimento['id_procedimento'];
                $objHistorico = $utlRegrasGeraisRN->recuperarObjHistorico($idProcedimento, InfraDTO::$TIPO_ORDENACAO_ASC);
                if ($objHistorico) {
                    $utlRegrasGeraisRN->migracaoHistoricoDsmp($objHistorico, $idProcedimento);
                }
            }
        }
    }

    protected function fixIndices(InfraMetaBD $objInfraMetaBD, $arrTabelas)
    {
        InfraDebug::getInstance()->setBolDebugInfra(true);

        $this->logar('ATUALIZANDO INDICES...');

        $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas);

        InfraDebug::getInstance()->setBolDebugInfra(false);
    }

}

try {

	SessaoSEI::getInstance(false);
    BancoSEI::getInstance()->setBolScript(true);

    $configuracaoSEI = new ConfiguracaoSEI();
    $arrConfig = $configuracaoSEI->getInstance()->getArrConfiguracoes();

    if (!isset($arrConfig['SEI']['Modulos'])) {
        throw new InfraException('PARÂMETRO DE MÓDULOS NO CONFIGURAÇÃO DO SEI NÃO DECLARADO');
    } else {
        $arrModulos = $arrConfig['SEI']['Modulos'];
        if (!key_exists('UtilidadesIntegracao', $arrModulos)) {
            throw new InfraException('MÓDULO UTILIDADES NÃO DECLARADO NO CONFIGURAÇÃO DO SEI');
        }
    }

    if (!class_exists('UtilidadesIntegracao')) {
        throw new InfraException('A CLASSE PRINCIPAL "UtilidadesIntegracao" DO MÓDULO NÃO FOI ENCONTRADA');
    }

    InfraScriptVersao::solicitarAutenticacao(BancoSei::getInstance());
    $objVersaoSeiRN = new MdUtlAtualizadorSeiRN();
    $objVersaoSeiRN->atualizarVersao();
    exit;

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}
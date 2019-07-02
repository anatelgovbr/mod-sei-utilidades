<?
/**
 * ANATEL
 *
 * 05/07/2018 - criado por jaqueline.mendes - CAST
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlAtualizadorSeiRN extends InfraRN
{
    private $numSeg = 0;
    private $versaoAtualDesteModulo = '1.0.0';
    private $nomeDesteModulo = 'M�DULO UTILIDADES';
    private $nomeParametroModulo = 'VERSAO_MODULO_UTILIDADES';
    private $historicoVersoes = array('1.0.0');

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    private function inicializar($strTitulo){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        try {
            @ini_set('zlib.output_compression', '0');
            @ini_set('implicit_flush', '1');
        } catch (Exception $e) {
        }

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
            $this->logar('TEMPO TOTAL DE EXECU��O: '.$this->numSeg.' s');
        } else {
            $strMsg = 'ERRO: '.$strMsg;
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

    protected function atualizarVersaoConectado(){

        try {
            $this->inicializar('INICIANDO A INSTALA��O/ATUALIZA��O DO '.$this->nomeDesteModulo.' NO SEI VERS�O '.SEI_VERSAO);

            //testando versao do framework
			$numVersaoInfraRequerida = '1.502';
            $versaoInfraFormatada = (int) str_replace('.','', VERSAO_INFRA);
            $versaoInfraReqFormatada = (int) str_replace('.','', $numVersaoInfraRequerida);

            if ($versaoInfraFormatada < $versaoInfraReqFormatada){
                $this->finalizar('VERS�O DO FRAMEWORK PHP INCOMPAT�VEL (VERS�O ATUAL '.VERSAO_INFRA.', SENDO REQUERIDA VERS�O IGUAL OU SUPERIOR A '.$numVersaoInfraRequerida.')',true);
            }

            //checando BDs suportados
			if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                !(BancoSEI::getInstance() instanceof InfraOracle)) {
					$this->finalizar('BANCO DE DADOS N�O SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);
            }

            //checando permissoes na base de dados
			$objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sei_teste')) == 0) {
                BancoSEI::getInstance()->executarSql('CREATE TABLE sei_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }

            BancoSEI::getInstance()->executarSql('DROP TABLE sei_teste');

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

            $strVersaoModuloUtilidades = $objInfraParametro->getValor($this->nomeParametroModulo, false);

            //VERIFICANDO QUAL VERSAO DEVE SER INSTALADA NESTA EXECUCAO
			if (InfraString::isBolVazia($strVersaoModuloUtilidades)) {
                $this->instalarv100();
                $this->logar('INSTALA��O/ATUALIZA��O DA VERS�O ' . $this->versaoAtualDesteModulo . ' DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
                $this->finalizar('FIM', false);
            }
            else {
                $this->logar('A VERS�O MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v ' . $this->versaoAtualDesteModulo . ') J� EST� INSTALADA.');
                $this->finalizar('FIM', false);
            }

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {
            
			var_dump($e);
			InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            $this->logar($e->getTraceAsString());
            $this->finalizar('FIM', true);
            print_r($e);
            die;
            throw new InfraException('Erro instalando/atualizando vers�o.', $e);
        }
    }

    //Contem atualiza��es da versao 1.0.0
    protected function instalarv100(){

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

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
				sin_ativo '.  $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
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
				sin_possui_analise ' .$objInfraMetaBD->tipoTextoFixo(1). ' NOT NULL,
				sta_encaminhamento_triagem '.$objInfraMetaBD->tipoTextoFixo(1). ' NULL,
				id_md_utl_adm_fila '.$objInfraMetaBD->tipoNumero(). ' NULL,
				sin_ativo '.$objInfraMetaBD->tipoTextoFixo(1). ' NOT NULL) '
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
				unidade_esforco '.$objInfraMetaBD->tipoNumero(). ' NOT NULL) '
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_utl_rel_triagem_atv', 'pk_md_utl_rel_triagem_atv', array('id_md_utl_rel_triagem_atv'));

        $this->logar('CRIANDO A SEQUENCE seq_md_utl_rel_triagem_atv');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_utl_rel_triagem_atv', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_utl_rel_triagem_atv', 'md_utl_rel_triagem_atv',
            array('id_md_utl_triagem'), 'md_utl_triagem', array('id_md_utl_triagem'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_utl_rel_triagem_atv', 'md_utl_rel_triagem_atv',
            array('id_md_utl_adm_atividade'), 'md_utl_adm_atividade', array('id_md_utl_adm_atividade'));


        //Iniciando tabela de An�lise
        $this->logar('CRIANDO A TABELA md_utl_analise');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_utl_analise (
				id_md_utl_analise ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
				informacoes_complementares ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL,
				sta_encaminhamento_analise '.$objInfraMetaBD->tipoTextoFixo(1). ' NULL,
				id_md_utl_adm_fila '.$objInfraMetaBD->tipoNumero(). ' NULL,
				sin_ativo '.$objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
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
				observacao '. $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL) '
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
				sin_ativo '.$objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
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
				observacao '. $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL) '
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

        // Tabela de Hist�rico de Controle Geral do DSMP
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
				sin_ultima_fila '. $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
				sin_ultimo_responsavel '. $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) '
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

        $this->logar('ADICIONANDO PAR�METRO '.$this->nomeParametroModulo.' NA TABELA infra_parametro PARA CONTROLAR A VERS�O DO M�DULO');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES( \'1.0.0\',  \'' . $this->nomeParametroModulo . '\' )');
    }
}

?>
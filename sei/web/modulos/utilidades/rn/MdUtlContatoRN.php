<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlContatoRN extends InfraRN {

    public function __construct() {
        parent::__construct ();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance ();
    }

    public static $STR_CONTATO_SISTEMA      = 'Sistemas';
    public static $STR_NOME_CONTATO_MODULO  = 'Usuário Automático do Sistema: Módulo de Utilidades';

    public static $STR_SIGLA_CONTATO_MODULO = 'Usuario_Utilidades';
    public static $STR_INFRA_PARAMETRO_SIGLA_CONTATO = 'MODULO_UTILIDADES_ID_USUARIO_SISTEMA';

    public static $STR_PAIS_CONTATO_MODULO = 'Brasil';
    public static $SIM = 'S';
    public static $NAO = 'N';

    protected function cadastrarControlado($objContatoDTO)
    {
        try {
            $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
            $objContatoDTO = $objContatoBD->cadastrar($objContatoDTO);

            return $objContatoDTO;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Contato.', $e);
        }
    }

    private function _setCamposNullsContato(&$objContatoDTO){

        $objContatoDTO->setStrStaGenero(null);
        $objContatoDTO->setDblCpf(null);
        $objContatoDTO->setDblRg(null);
        $objContatoDTO->setDblCnpj(null);
        $objContatoDTO->setNumIdCargo(null);
        $objContatoDTO->setStrOrgaoExpedidor(null);
        $objContatoDTO->setStrMatricula(null);
        $objContatoDTO->setStrMatriculaOab(null);
        $objContatoDTO->setDtaNascimento(null);
        $objContatoDTO->setStrTelefoneFixo(null);
        $objContatoDTO->setStrTelefoneCelular(null);
        $objContatoDTO->setStrEmail(null);
        $objContatoDTO->setStrSitioInternet(null);
        $objContatoDTO->setStrEndereco(null);
        $objContatoDTO->setStrComplemento(null);
        $objContatoDTO->setStrBairro(null);
        $objContatoDTO->setStrCep(null);
        $objContatoDTO->setStrObservacao(null);
        $objContatoDTO->setNumIdUf(null);
        $objContatoDTO->setNumIdCidade(null);
    }

    public function getIdxContatoUsuario(){
        $strIndexacao = '';
        $strIndexacao .= ' '.MdUtlContatoRN::$STR_SIGLA_CONTATO_MODULO;
        $strIndexacao .= ' '.MdUtlContatoRN::$STR_NOME_CONTATO_MODULO;
        $strIndexacao = InfraString::prepararIndexacao($strIndexacao);

        return $strIndexacao;
    }

    private function _getIdBrasil(){
        $objPaisRN = new PaisRN();

        $objPaisDTO = new PaisDTO();
        $objPaisDTO->setStrNome(MdUtlContatoRN::$STR_PAIS_CONTATO_MODULO);
        $objPaisDTO->retNumIdPais();
        $objPaisDTO = $objPaisRN->consultar($objPaisDTO);

        $idPais = !is_null($objPaisDTO) ? $objPaisDTO->getNumIdPais() : null;

        return $idPais;
    }

    private function _getTipoContatoSistema(){
        $objTipoContatoRN  = new TipoContatoRN();

        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->setStrNome(MdUtlContatoRN::$STR_CONTATO_SISTEMA);
        $objTipoContatoDTO->retNumIdTipoContato();

        $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);
        $idTpContato =  !is_null($objTipoContatoDTO) ? $objTipoContatoDTO->getNumIdTipoContato() : null;

        return $idTpContato;
    }

    protected function inserirContatoModuloUtlControlado(){
        try {
            $objContatoDTO = null;
            $idTpContato   = $this->_getTipoContatoSistema();
            $idPaisContato = $this->_getIdBrasil();

            $numProxSeq = $this->getObjInfraIBanco()->getValorSequencia('seq_contato');
            $idxContato    = $this->getIdxContatoUsuario();

            if($idTpContato && $numProxSeq){
                $objContatoDTO = new ContatoDTO();
                $objContatoDTO->setNumIdContato($numProxSeq);
                $objContatoDTO->setNumIdContatoAssociado($numProxSeq);
                $objContatoDTO->setNumIdTipoContato($idTpContato);
                $objContatoDTO->setDthCadastro(InfraData::getStrDataHoraAtual());
                $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_JURIDICA);
                $objContatoDTO->setStrNome(MdUtlContatoRN::$STR_NOME_CONTATO_MODULO);
                $objContatoDTO->setStrSigla(MdUtlContatoRN::$STR_SIGLA_CONTATO_MODULO);
                $objContatoDTO->setNumIdPais($idPaisContato);
                $objContatoDTO->setStrSinEnderecoAssociado(MdUtlContatoRN::$NAO);
                $objContatoDTO->setStrSinAtivo(MdUtlContatoRN::$SIM);
                $objContatoDTO->setStrIdxContato($idxContato);
                $this->_setCamposNullsContato($objContatoDTO);

                $objContatoDTO = $this->cadastrarControlado($objContatoDTO);
            }

            return $objContatoDTO;

        }catch (Exception $e)
        {
            throw new InfraException('Erro inserindo Contato.',$e);
        }
    }


}
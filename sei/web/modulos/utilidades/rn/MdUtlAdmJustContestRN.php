<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJustContestRN extends InfraRN
{
    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    protected function listarConectado(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO) {
        try {
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_listar');
            $objMdUtlAdmJustContestBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
           return $objMdUtlAdmJustContestBD->listar($objMdUtlAdmJustContestDTO);

        }catch(Exception $e){
            throw new InfraException('Erro listando Justificativa de Contestação.',$e);
        }
    }

    protected function cadastrarControlado(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO) {
        try{
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_contest_cadastrar', __METHOD__, $objMdUtlAdmJustContestDTO);
            $objInfraException = new InfraException();

            $this->validarStrNome($objMdUtlAdmJustContestDTO, $objInfraException);
            $this->validarStrDescricao($objMdUtlAdmJustContestDTO, $objInfraException);
            $this->validarDuplicacao($objMdUtlAdmJustContestDTO, $objInfraException);

            //$objInfraException->lancarValidacoes();

            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmJustContestBD->cadastrar($objMdUtlAdmJustContestDTO);

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando Justificativa de Contestação.',$e);
        }
    }

    private function validarStrNome(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlAdmJustContestDTO->getStrNome())){
            $objInfraException->adicionarValidacao('Nome não informado.');
        }else{
            $objMdUtlAdmJustContestDTO->setStrNome(trim($objMdUtlAdmJustContestDTO->getStrNome()));

            if (strlen($objMdUtlAdmJustContestDTO->getStrNome())>50){
                $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Nome', '50'));
                $objInfraException->adicionarValidacao($msg);
            }
        }
    }

    private function validarDuplicacao($objMdUtlAdmDTO, $objInfraException){
        $id = $objMdUtlAdmDTO->getNumIdMdUtlAdmJustContest();
        $objMdUtlAdmDTO2 = new MdUtlAdmJustContestDTO();
        $objMdUtlAdmDTO2->setStrNome($objMdUtlAdmDTO->getStrNome());
        $objMdUtlAdmDTO2->setBolExclusaoLogica(false);

        if(!is_null($id)){
            $objMdUtlAdmDTO2->setNumIdMdUtlAdmJustContest($id, InfraDTO::$OPER_DIFERENTE);
        }

        $idTpCtrl = $_POST['hdnIdTpCtrlUtl'];
        $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

        $existeRegistroDupl = $this->contar($objMdUtlAdmDTO2) > 0;

        if($existeRegistroDupl){
            
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_08, array('Justificativa de Contestação'));
            $objInfraException->lancarValidacao($msg);
        }
    }

    private function validarStrDescricao(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlAdmJustContestDTO->getStrDescricao())){
            $objInfraException->adicionarValidacao('Descrição não informada.');
        }else{
            $objMdUtlAdmJustContestDTO->setStrDescricao(trim($objMdUtlAdmJustContestDTO->getStrDescricao()));

            if (strlen($objMdUtlAdmJustContestDTO->getStrDescricao())>250){
                $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Descrição', '250'));
                $objInfraException->adicionarValidacao($msg);
            }
        }
    }

    protected function contarConectado(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO){
        try {
            //SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_listar');

            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmJustContestBD->contar($objMdUtlAdmJustContestDTO);

        }catch(Exception $e){
            throw new InfraException('Erro contando Justificativa de Contestação.',$e);
        }
    }

    protected function consultarConectado(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO){
        try {
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_consultar');
            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
           return $objMdUtlAdmJustContestBD->consultar($objMdUtlAdmJustContestDTO);
        }catch(Exception $e){
            throw new InfraException('Erro consultando Justificativa de Contestação.',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmJustContestDTO $objMdUtlAdmJustContestDTO){
        try {
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_contest_alterar', __METHOD__, $objMdUtlAdmJustContestDTO);
            $objInfraException = new InfraException();

            if ($objMdUtlAdmJustContestDTO->isSetStrNome()){
                $this->validarStrNome($objMdUtlAdmJustContestDTO, $objInfraException);
            }
            if ($objMdUtlAdmJustContestDTO->isSetStrDescricao()){
                $this->validarStrDescricao($objMdUtlAdmJustContestDTO, $objInfraException);
            }
            $this->validarDuplicacao($objMdUtlAdmJustContestDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            $objMdUtlAdmJustContestBD->alterar($objMdUtlAdmJustContestDTO);
        }catch(Exception $e){
            throw new InfraException('Erro alterando Justificativa de Contestação.',$e);
        }
    }

    protected function desativarControlado($arrObjMdUtlAdmJustContestDTO){
        try {
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_desativar');
            $objInfraException = new InfraException();
            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmJustContestDTO);$i++){
                    $objMdUtlAdmJustContestBD->desativar($arrObjMdUtlAdmJustContestDTO[$i]);
            }
        }catch(Exception $e){
            throw new InfraException('Erro desativando Justificativa de Contestação.',$e);
        }
    }

    protected function reativarControlado($arrObjMdUtlAdmJustContestDTO){
        try {
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_contest_reativar', __METHOD__, $arrObjMdUtlAdmJustContestDTO);
            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmJustContestDTO);$i++){
                $objMdUtlAdmJustContestBD->reativar($arrObjMdUtlAdmJustContestDTO[$i]);
            }
        }catch(Exception $e){
            throw new InfraException('Erro reativando Justificativa de Contestação.',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmJustContestDTO){
        try {
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_contest_excluir', __METHOD__, $arrObjMdUtlAdmJustContestDTO);
            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmJustContestDTO);$i++){
                    $objMdUtlAdmJustContestBD->excluir($arrObjMdUtlAdmJustContestDTO[$i]);
            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo Justificativa de Contestação.',$e);
        }
    }

    protected function validarExclusaoJustContestConectado($idJustContest){
        $objContestacaoDTO = new MdUtlContestacaoDTO();
        $objContestacaoDTO->setNumIdMdUtlAdmJustContest($idJustContest);
        $objContestacaoDTO->retTodos();

        $objContestacaoRN = new MdUtlContestacaoRN();
        $isContestacao = $objContestacaoRN->contar($objContestacaoDTO) > 0;

        if($isContestacao){
            $objInfraException= new InfraException();
            $msg = MdUtlMensagemINT::$MSG_UTL_106;
            $objInfraException->lancarValidacao($msg);
            return true;
        }

        return $isContestacao;

    }


}
<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmDsRN extends InfraRN
{

    /*Variáveis para validação de Priorização*/
    public static $SIM = 'S';
    public static $STR_SIM = 'Sim';

    public static $NAO = 'N';
    public static $STR_NAO = 'Não';
    /*---------------------------------------*/

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarSinPriorizarDistribuicao(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinPriorizarDistribuicao())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar Distribuição por Prazo para Resposta indicado na Triagem.');
        }
    }
    private function validarSinFila(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinFila())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar por Fila.');
        }
    }
    private function validarSinStatusAtendimentoDsmp(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinStatusAtendimentoDsmp())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar por Status.');
        }
    }
    private function validarSinAtividade(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinAtividade())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar por Atividade.');
        }
    }
    private function validarSinTipoProcesso(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinTipoProcesso())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar por Tipo de Processo.');
        }
    }
    private function validarSinDiasUteis(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdUtlAdmPrmDsDTO->getStrSinDiasUteis())){
            $objInfraException->adicionarValidacao('Informe o campo Priorizar por Dias Úteis.');
        }
    }

    public function cadastrarParemetrizacao(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO, $idMdUtlAdmPrmDsDTO) {

        if ($idMdUtlAdmPrmDsDTO > 0) {
            $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmPrmDs($idMdUtlAdmPrmDsDTO);
            $this->alterar($objMdUtlAdmPrmDsDTO);
            $this->cadastrarPriorizacaoFilaControlado($objMdUtlAdmPrmDsDTO);
            $this->cadastrarPriorizacaoStatusControlado($objMdUtlAdmPrmDsDTO);
            $this->cadastrarPriorizacaoAtividadeControlado($objMdUtlAdmPrmDsDTO);
            $this->cadastrarPriorizacaoTipoProcessoControlado($objMdUtlAdmPrmDsDTO);
        } else {
            $ret = $this->cadastrar($objMdUtlAdmPrmDsDTO);
            $this->cadastrarPriorizacaoFilaControlado($ret);
            $this->cadastrarPriorizacaoStatusControlado($ret);
            $this->cadastrarPriorizacaoAtividadeControlado($ret);
            $this->cadastrarPriorizacaoTipoProcessoControlado($ret);
        }
    }

    protected function cadastrarPriorizacaoFilaControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        $objMdUtlAdmRelPrmDsFilaRN = new MdUtlAdmRelPrmDsFilaRN();
        $objMdUtlAdmRelPrmDsFilaDTO = new MdUtlAdmRelPrmDsFilaDTO();

        $arrFila = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnFila']);

        if(count($arrFila) > 0) {
            if($_POST['selFila'] == MdUtlAdmPrmDsRN::$SIM) {
                $this->excluirPriorizacaoFilaControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
            }
            foreach($arrFila as $linha){
                $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmParamDs($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmFila($linha[0]);
                $objMdUtlAdmRelPrmDsFilaDTO->setNumPrioridade($linha[2]);

                if($_POST['selFila'] == MdUtlAdmPrmDsRN::$SIM) {
                    $objMdUtlAdmRelPrmDsFilaRN->cadastrar($objMdUtlAdmRelPrmDsFilaDTO);
                } else {
                    $this->excluirPriorizacaoFilaControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                }
            }
        }else{
            $this->excluirPriorizacaoFilaControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
        }
    }

    protected function excluirPriorizacaoFilaControlado($idParametrizacaoDs)
    {
        $objMdUtlAdmRelPrmDsFilaDTO = new MdUtlAdmRelPrmDsFilaDTO();
        $objMdUtlAdmRelPrmDsFilaDTO->retTodos();
        $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmParamDs($idParametrizacaoDs);

        $objMdUtlAdmRelPrmDsFilaRN = new MdUtlAdmRelPrmDsFilaRN();
        $arrobjMdUtlAdmRelPrmDsFilaDTO = $objMdUtlAdmRelPrmDsFilaRN->listar($objMdUtlAdmRelPrmDsFilaDTO);
        $objMdUtlAdmRelPrmDsFilaRN->excluir($arrobjMdUtlAdmRelPrmDsFilaDTO);
    }


    protected function cadastrarPriorizacaoStatusControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        $objMdUtlAdmRelPrmDsAtenDTO = new MdUtlAdmRelPrmDsAtenDTO();
        $objMdUtlAdmRelPrmDsAtenRN = new MdUtlAdmRelPrmDsAtenRN();

        $arrStatus = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnStatus']);
        if(count($arrStatus) > 0) {
            if ($_POST['selStatus'] == MdUtlAdmPrmDsRN::$SIM){
                $this->excluirPriorizacaoStatusControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
            }
            foreach($arrStatus as $linha){
                $objMdUtlAdmRelPrmDsAtenDTO->setNumIdMdUtlAdmParamDs($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                $objMdUtlAdmRelPrmDsAtenDTO->setNumStaAtendimentoDsmp($linha[0]);
                $objMdUtlAdmRelPrmDsAtenDTO->setNumPrioridade($linha[2]);

                if($_POST['selStatus'] == MdUtlAdmPrmDsRN::$SIM) {
                    $objMdUtlAdmRelPrmDsAtenRN->cadastrar($objMdUtlAdmRelPrmDsAtenDTO);
                } else {
                    $this->excluirPriorizacaoStatusControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                }
            }
        }else{
            $this->excluirPriorizacaoStatusControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
        }
    }

    protected function excluirPriorizacaoStatusControlado($idParametrizacaoDs)
    {
        $objMdUtlAdmRelPrmDsAtenDTO = new MdUtlAdmRelPrmDsAtenDTO();
        $objMdUtlAdmRelPrmDsAtenDTO->retTodos();
        $objMdUtlAdmRelPrmDsAtenDTO->setNumIdMdUtlAdmParamDs($idParametrizacaoDs);

        $objMdUtlAdmRelPrmDsAtenRN = new MdUtlAdmRelPrmDsAtenRN();
        $arrobjMdUtlAdmRelPrmDsAtenDTO = $objMdUtlAdmRelPrmDsAtenRN->listar($objMdUtlAdmRelPrmDsAtenDTO);
        $objMdUtlAdmRelPrmDsAtenRN->excluir($arrobjMdUtlAdmRelPrmDsAtenDTO);
    }


    protected function cadastrarPriorizacaoAtividadeControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        $objMdUtlAdmRelPrmDsATivDTO = new MdUtlAdmRelPrmDsAtivDTO();
        $objMdUtlAdmRelPrmDsATivRN = new MdUtlAdmRelPrmDsAtivRN();

        $arrAtividade = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnAtividade']);

        if(count($arrAtividade) > 0) {
            if ($_POST['selAtividade'] == MdUtlAdmPrmDsRN::$SIM) {
                $this->excluirPriorizacaoAtividadeControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
            }
            foreach($arrAtividade as $linha){
                $objMdUtlAdmRelPrmDsATivDTO->setNumIdMdUtlAdmParamDs($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                $objMdUtlAdmRelPrmDsATivDTO->setNumIdMdUtlAdmAtividade($linha[0]);
                $objMdUtlAdmRelPrmDsATivDTO->setNumPrioridade($linha[2]);

                if($_POST['selAtividade'] == MdUtlAdmPrmDsRN::$SIM) {
                    $objMdUtlAdmRelPrmDsATivRN->cadastrar($objMdUtlAdmRelPrmDsATivDTO);
                } else {
                    $this->excluirPriorizacaoAtividadeControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                }
            }
        }else{
            $this->excluirPriorizacaoAtividadeControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
        }
    }

    protected function excluirPriorizacaoAtividadeControlado($idParametrizacaoDs)
    {
        $objMdUtlAdmRelPrmDsATivDTO = new MdUtlAdmRelPrmDsAtivDTO();
        $objMdUtlAdmRelPrmDsATivDTO->retTodos();
        $objMdUtlAdmRelPrmDsATivDTO->setNumIdMdUtlAdmParamDs($idParametrizacaoDs);

        $objMdUtlAdmRelPrmDsATivRN = new MdUtlAdmRelPrmDsAtivRN();
        $arrobjMdUtlAdmRelPrmDsAtivDTO = $objMdUtlAdmRelPrmDsATivRN->listar($objMdUtlAdmRelPrmDsATivDTO);
        $objMdUtlAdmRelPrmDsATivRN->excluir($arrobjMdUtlAdmRelPrmDsAtivDTO);
    }


    protected function cadastrarControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        try {
            //Valida Permissão
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_ds_cadastrar', __METHOD__, $objMdUtlAdmPrmDsDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarSinAtividade($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinFila($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinPriorizarDistribuicao($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinStatusAtendimentoDsmp($objMdUtlAdmPrmDsDTO,$objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinTipoProcesso($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinDiasUteis($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmDsBD->cadastrar($objMdUtlAdmPrmDsDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Parametrização de Distribuição', $e);
        }
    }

    protected function alterarControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        try {
            //Valida Permissão
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_ds_cadastrar', __METHOD__, $objMdUtlAdmPrmDsDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarSinAtividade($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinFila($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinPriorizarDistribuicao($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinStatusAtendimentoDsmp($objMdUtlAdmPrmDsDTO,$objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinTipoProcesso($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();
            $this->validarSinDiasUteis($objMdUtlAdmPrmDsDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmDsBD->alterar($objMdUtlAdmPrmDsDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Parametrização de Distribuição', $e);
        }
    }

    protected function consultarConectado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        try {
            //Valida Permissão
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_ds_consultar', __METHOD__, $objMdUtlAdmPrmDsDTO);

            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmDsBD->consultar($objMdUtlAdmPrmDsDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro consultando Parametrização de Distribuição', $e);
        }
    }

    protected function desativarControlado($arrObjMdUtlAdmPrmDsDTO) {
        try {
            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            for ($i=0;$i<count($arrObjMdUtlAdmPrmDsDTO);$i++) {
                $objMdUtlAdmPrmDsBD->desativar($arrObjMdUtlAdmPrmDsDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Parametrização de Distribuição', $e);
        }
    }

    protected function reativarControlado($arrObjMdUtlAdmPrmDsDTO) {
        try {
            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            for ($i=0;$i<count($arrObjMdUtlAdmPrmDsDTO);$i++) {
                $objMdUtlAdmPrmDsBD->reativar($arrObjMdUtlAdmPrmDsDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro reativando Parametrização de Distribuição', $e);
        }
    }

    protected function listarConectado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        try {
            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmDsBD->listar($objMdUtlAdmPrmDsDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Parametrização de Distribuição', $e);
        }
    }

    protected function contarConectado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        try {

//            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_ds_cadastrar');

            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmDsBD->contar($objMdUtlAdmPrmDsDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro contando. ', $e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmPrmDsDTO){
        try {

            //Valida Permissao
            //SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_excluir', _METHOD_, $arrObjMdUtlAdmRelPrmDsAtenDTO);

            $objMdUtlAdmPrmDsBD = new MdUtlAdmPrmDsBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmPrmDsDTO);$i++){
                $obj = $objMdUtlAdmPrmDsBD->excluir($arrObjMdUtlAdmPrmDsDTO[$i]);

            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function cadastrarPriorizacaoTipoProcessoControlado(MdUtlAdmPrmDsDTO $objMdUtlAdmPrmDsDTO) {
        $objMdUtlAdmRelPrmDsProcDTO = new MdUtlAdmRelPrmDsProcDTO();
        $objMdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();

        $arrTipoProcesso = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTipoProcesso']);

        if(count($arrTipoProcesso) > 0) {
            if ($_POST['selTipoProcesso'] == MdUtlAdmPrmDsRN::$SIM) {
                $this->excluirPriorizacaoTipoProcessoControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
            }
            foreach($arrTipoProcesso as $linha){
                $objMdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmParamDs($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                $objMdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmTipoProcesso($linha[0]);
//                alterar aqui o priorização
                $objMdUtlAdmRelPrmDsProcDTO->setNumPrioridade($linha[2]);

                if($_POST['selTipoProcesso'] == MdUtlAdmPrmDsRN::$SIM) {
                    $objMdUtlAdmRelPrmDsProcRN->cadastrar($objMdUtlAdmRelPrmDsProcDTO);
                } else {
                    $this->excluirPriorizacaoTipoProcessoControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
                }
            }
        }else{
            $this->excluirPriorizacaoTipoProcessoControlado($objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs());
        }
    }

    protected function excluirPriorizacaoTipoProcessoControlado($idParametrizacaoDs)
    {
        $objMdUtlAdmRelPrmDsProcDTO = new MdUtlAdmRelPrmDsProcDTO();
        $objMdUtlAdmRelPrmDsProcDTO->retTodos();
        $objMdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmParamDs($idParametrizacaoDs);

        $objMdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();
        $arrobjMdUtlAdmRelPrmDsAtivDTO = $objMdUtlAdmRelPrmDsProcRN->listar($objMdUtlAdmRelPrmDsProcDTO);
        $objMdUtlAdmRelPrmDsProcRN->excluir($arrobjMdUtlAdmRelPrmDsAtivDTO);
    }

    public function getPrioridades($idTipoControle) {
        try {
            $objMdUtlAdmPrmDsDTO = new MdUtlAdmPrmDsDTO();
            $objMdUtlAdmPrmDsDTO->retTodos();
            $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

            return $this->consultar($objMdUtlAdmPrmDsDTO);
        } catch (Exception $e) {
            throw new InfraException('Erro Prioridade geral na Parametrização de Distribuição', $e);
        }
    }
}

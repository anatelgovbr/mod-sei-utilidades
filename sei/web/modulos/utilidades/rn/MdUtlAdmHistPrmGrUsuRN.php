<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmHistPrmGrUsuRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_hist_prm_gr_cadastrar',__METHOD__,$objMdUtlAdmHistPrmGrDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmGrBD->cadastrar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_hist_prm_gr_alterar',__METHOD__, $objMdUtlAdmHistPrmGrDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $objMdUtlAdmPrmGrBD->alterar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro alterando Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function contarConectado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_hist_prm_gr_consultar');

            $objMdUtlHistAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlHistAdmPrmGrBD->contar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando os registros do Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function listarConectado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_hist_prm_gr_listar');

            $objMdUtlHistAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlHistAdmPrmGrBD->listar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro listando os registros do Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function clonarObjParametroParaHistoricoConectado($objDTOAtual){
        $objHistoricoDTO = new MdUtlAdmHistPrmGrUsuDTO();

        foreach($objDTOAtual->getArrAtributos() as $attr){
            $strValor    = $attr[InfraDTO::$POS_ATRIBUTO_VALOR];
            $strAtributo = $attr[InfraDTO::$FLAG_SET];
            if($strAtributo == 'IdMdUtlAdmPrmGrUsu'){
                $objHistoricoDTO->set('IdMdUtlAdmHistPrmGrUsu', null);
            }else {
                $objHistoricoDTO->set($strAtributo, $strValor);
            }
        }

        return $objHistoricoDTO;
    }


    protected function migrarDadosExistentesParamHistoricoControlado(){
        $objMdUtlPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlHistPrmGrUsuRN = new MdUtlAdmHistPrmGrUsuDTO();
        $dthSolicitada = '31-05-2019 00:01:00';
        $objMdUtlPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlPrmGrUsuDTO->retTodos();

        $isPossuiRegistro  = $objMdUtlPrmGrUsuRN->contar($objMdUtlPrmGrUsuDTO) > 0;

        if ($isPossuiRegistro) {
            $objUsuarioRN = new MdUtlUsuarioRN();
            $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

            $arrObjs = $objMdUtlPrmGrUsuRN->listar($objMdUtlPrmGrUsuDTO);

            foreach ($arrObjs as $objDTO) {

                $objMdUtlAdmHistPrmGrUsuDTO = $this->clonarObjParametroParaHistorico($objDTO);
                $objMdUtlAdmHistPrmGrUsuDTO->setDthInicial($dthSolicitada);
                $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuarioAtual($objUsuarioDTO->getNumIdUsuario());
                $this->cadastrar($objMdUtlAdmHistPrmGrUsuDTO);
            }
        }

    }

    protected function excluirControlado($arrObjMdUtlAdmHistPrmGrUsuDTO){
        try {
            $objMdUtlAdmPrmContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmHistPrmGrUsuDTO);$i++){
                $objMdUtlAdmPrmContestBD->excluir($arrObjMdUtlAdmHistPrmGrUsuDTO[$i]);
            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo Parâmetro de Contestação.',$e);
        }
    }

    public function configObjParams( $id_prm_gr ){
        $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();

        $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr( $id_prm_gr );

        $objMdUtlAdmHistPrmGrUsuDTO->retNumIdMdUtlAdmHistPrmGrUsu();
        $objMdUtlAdmHistPrmGrUsuDTO->retNumIdUsuario();
        $objMdUtlAdmHistPrmGrUsuDTO->retDthFinal();
        $objMdUtlAdmHistPrmGrUsuDTO->retStrNome();
        $objMdUtlAdmHistPrmGrUsuDTO->retStrSigla();
        $objMdUtlAdmHistPrmGrUsuDTO->retDthInicioParticipacao();
        $objMdUtlAdmHistPrmGrUsuDTO->retDthFimParticipacao();
        $objMdUtlAdmHistPrmGrUsuDTO->retDblIdDocumento();

        $objMdUtlAdmHistPrmGrUsuDTO->setOrd('IdUsuario' , InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlAdmHistPrmGrUsuDTO->setOrd('IdMdUtlAdmHistPrmGrUsu' , InfraDTO::$TIPO_ORDENACAO_ASC);

        return $objMdUtlAdmHistPrmGrUsuDTO;
    }

    public function getExParticipantesTipoCtrl( $id_prm_gr ){
        $arrObjs = $this->listar( $this->configObjParams( $id_prm_gr ) );
        $qtdReg = count($arrObjs);

        if( $qtdReg == 0 ) return $arrObjs;

        //retorna usuarios que estao com o campo dth_final preenchido, ou seja, nao faz mais parte do Tipo de Ctrl, em principio
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        for( $i = 0 ; $i < $qtdReg ; $i++ ){
            $arrObjs[$i]->setDblIdDocumento( $objMdUtlAdmPrmGrUsuRN->getNumeroSeiPlanoTrabalho(
                    $arrObjs[$i]->getDblIdDocumento()
                )
            );

            if( array_key_exists($i + 1 , $arrObjs ) && $arrObjs[$i]->getNumIdUsuario() == $arrObjs[$i + 1]->getNumIdUsuario() ) {
                unset($arrObjs[$i]);
            }elseif( empty( $arrObjs[$i]->getDthFinal() ) ){
                unset($arrObjs[$i]);
            }
        }

        //se estiver vazio, retorna o proprio array de objetos vazio
        if( empty( $arrObjs ) ) return $arrObjs;

        //consulta usuarios que estao ativos no Tipo de Ctrl, filtrando a consulta com os dados de retorno acima
        $arrIdsUsuarioHist = InfraArray::converterArrInfraDTO( $arrObjs , 'IdUsuario');

        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuRN  = new MdUtlAdmPrmGrUsuRN();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr( $id_prm_gr );
        $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario( $arrIdsUsuarioHist , InfraDTO::$OPER_IN );
        $objMdUtlAdmPrmGrUsuDTO->retNumIdUsuario();

        $arrObjsPrmGrUsu = $objMdUtlAdmPrmGrUsuRN->listar( $objMdUtlAdmPrmGrUsuDTO );

        //se nao encontrou usuarios na parametrizacao, de fato, todos os usuarios encontrados no começo da função estão
        //inativos, com isso, retorna os dados da primeira consulta
        if( empty( $arrObjsPrmGrUsu ) ) return $arrObjs;

        //caso tenha usuarios na parametrização e que estão com registros finalizados no historico, efetua uma limpeza
        //nos dados da primeira consulta, removendo os usuarios que estão ativos
        $arrIdsUsuario = InfraArray::converterArrInfraDTO($arrObjsPrmGrUsu,'IdUsuario');

        foreach ( $arrObjs as $k => $v ){
            if( in_array( $v->getNumIdUsuario() , $arrIdsUsuario ) ) unset( $arrObjs[$k] );
        }

        return $arrObjs;
    }

    public function atualizarRegistroHistControlado(){
        try {
            $objHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
            $objHistPrmGrUsuDTO->setNumIdMdUtlAdmHistPrmGrUsu( $_POST['hdnIdHistPrmGrUsu'] );

            if (!empty($_POST['dthIniPart']))
                $objHistPrmGrUsuDTO->setDthInicioParticipacao( $_POST['dthIniPart'] );

            if (!empty($_POST['dthFimPart']))
                $objHistPrmGrUsuDTO->setDthFimParticipacao( $_POST['dthFimPart'] );

            if (!empty($_POST['planoTrab']))
                $objHistPrmGrUsuDTO->setDblIdDocumento( ( new MdUtlAdmPrmGrRN() )->getObjDocumentoNumSei( $_POST['planoTrab'] ) );

            $this->alterar( $objHistPrmGrUsuDTO );
            return true;
        }catch ( Exception $e ){
            throw new InfraException('Erro ao atualizar registro do Histórico da Parametrização Geral do Usuário.',$e);
        }
    }
}
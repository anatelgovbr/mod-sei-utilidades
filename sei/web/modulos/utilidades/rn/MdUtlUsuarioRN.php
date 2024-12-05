<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlUsuarioRN extends InfraRN {

    public function __construct() {
        parent::__construct ();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance ();
    }

    public static $SIM = 'S';
    public static $NAO = 'N';

    private function _getIdOrgaoPrincipal()
    {
        $idOrgao = null;
        $objInfraConfiguracao = ConfiguracaoSEI::getInstance();
        $sessaoSei = $objInfraConfiguracao->getValor('SessaoSEI');

        if (is_array($sessaoSei) && array_key_exists('SiglaOrgaoSistema', $sessaoSei)) {
            $sigla = $sessaoSei['SiglaOrgaoSistema'];

            if ($sigla != '')
            {
                $objOrgaoRN  = new OrgaoRN();
                $objOrgaoDTO = new OrgaoDTO();
                $objOrgaoDTO->setStrSigla($sigla);
                $objOrgaoDTO->retNumIdOrgao();

                $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

                if($objOrgaoDTO){
                    $idOrgao =  $objOrgaoDTO->getNumIdOrgao();
                }
            }
        }
        return $idOrgao;
    }

    private function _inserirNovoUsuarioInfraParametro($objUsuarioDTO){
        $objInfraParametroRN = new InfraParametroRN();
        $objInfraParametroDTO = new InfraParametroDTO();
        $objInfraParametroDTO->setStrNome(MdUtlContatoRN::$STR_INFRA_PARAMETRO_SIGLA_CONTATO);
        $objInfraParametroDTO->setStrValor($objUsuarioDTO->getNumIdUsuario());
        $objInfraParametroDTO = $objInfraParametroRN->cadastrar($objInfraParametroDTO);

        return $objInfraParametroDTO;
    }

    protected function realizarInsercoesUsuarioModuloUtlConectado()
    {
        $objMdUtlContatoRN = new MdUtlContatoRN();
        $objContatoDTO = $objMdUtlContatoRN->inserirContatoModuloUtl();

        if(!is_null($objContatoDTO)){
            $objUsuarioDTO = $this->_inserirUsuarioModuloUtilidades($objContatoDTO);
        }

        if(!is_null($objUsuarioDTO)){
            $objInfraParametroDTO = $this->_inserirNovoUsuarioInfraParametro($objUsuarioDTO);
        }

        if(!is_null($objInfraParametroDTO)){
            return true;
        }
    }

    private function _inserirUsuarioModuloUtilidades($objContatoDTO){
        $objUsuarioDTO   = null;
        $objContatoUtlRN = new MdUtlContatoRN();
        $objUsuarioRN    = new UsuarioRN();

        $idOrgaoPrinc = $this->_getIdOrgaoPrincipal();

        if (!is_null($idOrgaoPrinc) && !is_null($objContatoDTO))
        {
            $idxUsuario = $objContatoUtlRN->getIdxContatoUsuario();

            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->setNumIdUsuario(null);
            $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());
            $objUsuarioDTO->setStrIdOrigem(null);
            $objUsuarioDTO->setNumIdOrgao($idOrgaoPrinc);
            $objUsuarioDTO->setStrSigla(MdUtlContatoRN::$STR_SIGLA_CONTATO_MODULO);
            $objUsuarioDTO->setStrNome(MdUtlContatoRN::$STR_NOME_CONTATO_MODULO);
            $objUsuarioDTO->setStrIdxUsuario($idxUsuario);
            $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);
            $objUsuarioDTO->setStrSenha(null);
            $objUsuarioDTO->setStrSinAtivo(MdUtlUsuarioRN::$SIM);

            $objUsuarioDTO = $objUsuarioRN->cadastrarRN0487($objUsuarioDTO);
        }

        return $objUsuarioDTO;
    }

    protected function getObjUsuarioUtilidadesConectado($retId = false)
    {
        $objUsuarioDTO     = null;
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        $idUsuario = $objInfraParametro->getValor(MdUtlContatoRN::$STR_INFRA_PARAMETRO_SIGLA_CONTATO, false);

        if($idUsuario != '' && !is_null($idUsuario)){
            $objUsuarioRN  = new UsuarioRN();

            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->setNumIdUsuario($idUsuario);
            if($retId){
                $objUsuarioDTO->retNumIdUsuario();
            }else{
                $objUsuarioDTO->retTodos();
            }

            $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

            if($retId && $objUsuarioDTO)
            {
                return $objUsuarioDTO->getNumIdUsuario();
            }
        }

        return $objUsuarioDTO;
    }

}
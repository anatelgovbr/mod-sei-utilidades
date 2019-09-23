<?php
/**
 *  ANATEL
 *
 *  25/02/2016 - criado por marcelo.bezerra - CAST
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlAdmPermissaoRN extends InfraRN
{

    public static $NOME_GESTOR_CTRL_UTL = 'Gestor de Controle de Desempenho';
    public static $DESC_GESTOR_CTRL_UTL = 'Acesso aos recursos específicos de Gestor de Controle de Desempenho do Módulo Utilidades do SEI.';
    
    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function isAdmConectado()
    {
        //a partir do id do usuario consultar se ele é administrador ou nao
        $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();

        $objInfraSip  = new InfraSip(SessaoSEI::getInstance());
        $idUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        $arrPerfisSip = $objInfraSip->carregarPerfis(SessaoSEI::getInstance()->getNumIdSistema(), $idUsuario, $idUnidade);

        for ($i = 0; $i < count($arrPerfisSip); $i++) {

            if ($arrPerfisSip[$i][1] == 'Administrador') {
                return true;
            }

        }

        return false;
    }

    protected function isGestorConectado()
    {
      return SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_fila_selecionar');
    }

    protected function isGestorControleUtilidadesSipConectado()
    {
        $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
        $idUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();

        $objInfraSip  = new InfraSip(SessaoSEI::getInstance());

        $arrPerfisSip = $objInfraSip->carregarPerfis(SessaoSEI::getInstance()->getNumIdSistema(), $idUsuario, $idUnidade);

        for ($i = 0; $i < count($arrPerfisSip); $i++) {

            if ($arrPerfisSip[$i][1] == 'Gestor de Controle Utilidades') {
                return true;
            }

        }

        return false;
    }


}

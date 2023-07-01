<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsProcINT extends InfraINT {

    public static function consultarVinculoProcDistribuicao($idVinculo, $idControle){

        $mdUtlAdmPrmDsDTO = new MdUtlAdmPrmDsDTO();
        $mdUtlAdmPrmDsRN = new MdUtlAdmPrmDsRN();

        $mdUtlAdmPrmDsDTO->setNumIdMdUtlAdmTpCtrlDesemp($idControle);
        $mdUtlAdmPrmDsDTO->retNumIdMdUtlAdmPrmDs();

        $controle = $mdUtlAdmPrmDsRN->listar($mdUtlAdmPrmDsDTO);
        
        if(!empty($controle)){
            $mdUtlAdmRelPrmDsProcDTO = new MdUtlAdmRelPrmDsProcDTO();
            $mdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();

            $mdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmParamDs($controle[0]->getNumIdMdUtlAdmPrmDs());
            $mdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmTipoProcesso($idVinculo);
            $mdUtlAdmRelPrmDsProcDTO->retTodos();

            $numRegistro = $mdUtlAdmRelPrmDsProcRN->consultar($mdUtlAdmRelPrmDsProcDTO);

            $xml = '<dados>';
            if($numRegistro >0) {
                $xml .= '<sucesso>1</sucesso>';
                $xml .= '<msg>';
                $xml .= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_117);
                $xml .= '</msg>';
            }else{
                $xml .= '<sucesso>0</sucesso>';
            }
        }else{
            $xml = '<dados><sucesso>0</sucesso>';
        }   

        $xml .='</dados>';
        return $xml;
    }

}

<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAjustePrazoINT extends InfraINT {

  public static function calcularPrazoJustificativa($prazoDiasSolicitado, $idControleDesemp, $tipoSolicitacao, $dtPrazoSolicitada, $isStrPrazo, $dthPrazoAtual){
      
      $objMdUtlPrazoRN = new MdUtlPrazoRN();
      $objRegrasGerais = new MdUtlRegrasGeraisRN();

      $msg         = '';
      $dtPrazoData = '';
      $prazoDias   = '';
      $dtHora      = InfraData::getStrDataHoraAtual();
      $arrData     = explode(' ',$dthPrazoAtual);
      $dthPrazoAtual = $arrData[0]. ' 00:00:00';

      $isValido = true;

      if (!is_null($tipoSolicitacao)) {

          $isPrazo = $isStrPrazo == 1;

          if ($isPrazo) {
              $prazoDias = $prazoDiasSolicitado;
              $dtPrazoData = $objMdUtlPrazoRN->somarDiaUtil($prazoDias, $dthPrazoAtual);

          } else {

              $dtPrazoData = $dtPrazoSolicitada;
              $dtPrazoSolHr  = $dtPrazoSolicitada. ' 00:00:00';

              $comp = InfraData::compararDataHorasSimples($dthPrazoAtual, $dtPrazoSolHr);

              if($comp == -1 || $comp == 0){
                  $isValido = false;
                  $prazoDias = 0;
                  $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_94);
              }else{
                  $prazoDias = $objMdUtlPrazoRN->retornaQtdDiaUtil($dthPrazoAtual, $dtPrazoSolicitada);

              }

          }

          if($isValido) {
              $isValido = $objRegrasGerais->validarPrazoJustificativa($tipoSolicitacao, $prazoDias, $idControleDesemp);

              if (!$isValido) {
                  $arr = MdUtlControleDsmpINT::retornaSelectTipoSolicitacao();
                  $valor = $arr[$tipoSolicitacao];
                  $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_86, $valor);
              }

          }


          if ($isValido && !$isPrazo) {
              $arrFeriados = $objMdUtlPrazoRN->recuperarFeriados($dtHora, $dtPrazoData);

              if (InfraData::obterDescricaoDiaSemana($dtPrazoData) == 'sábado' ||
                  InfraData::obterDescricaoDiaSemana($dtPrazoData) == 'domingo' || in_array($dtPrazoData, $arrFeriados)){
                        $isValido = false;
                        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_91);
                  }
          }
      }


      $xml = '<Dados>';
      if($isValido) {
          $xml .= '<PrazoData>';
          $xml .= $dtPrazoData;
          $xml .= '</PrazoData>';
          $xml .= '<PrazoDias>';
          $xml .= $prazoDias;
          $xml .= '</PrazoDias>';
          $xml .= '<Sucesso>1</Sucesso>';
      }else{
          $xml .= '<Msg>';
          $xml .= $msg;
          $xml .= '</Msg>';
          $xml .= '<Sucesso>0</Sucesso>';
      }

      $xml .= '</Dados>';
      return $xml;
  }




}

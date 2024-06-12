<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegracaoINT extends InfraINT {

  public static function montarOperacaoSOAP($data){
    $enderecoWSDL = $data['urlServico'];
    $xml          = "<operacoes>\n";
    try{
        if ( !filter_var( $enderecoWSDL , FILTER_VALIDATE_URL ) || !InfraUtil::isBolUrlValida( $enderecoWSDL , FILTER_VALIDATE_URL ) )
          throw new InfraException("Endereço do WebService inválido!");

        if ( $data['tipoWs'] != 'SOAP' )
          throw new InfraException('O tipo de integração informado deve ser do tipo SOAP.');

        $client = new MdUtlSoapClienteRN($enderecoWSDL, 'wsdl');
        $client->setSoapVersion($data['versaoSoap']);
        $operacaoArr = $client->getFunctions();

        if(empty($operacaoArr)){
            $xml .= "<success>false</success>\n";
            $xml .= "<msg>Não existe operação.</msg>\n";
            $xml .= "</operacoes>\n";
            return $xml;
        }

        $xml .= "<success>true</success>\n";
        asort($operacaoArr);
        foreach ($operacaoArr as $key=>$operacao){
            $xml .= "<operacao key='{$key}'>{$operacao}</operacao>\n";
        }
        $xml .= '</operacoes>';
        return $xml;

    } catch( Exception $e ){
      throw new InfraException("Erro Operação SOAP: {$e->getMessage()}",$e);
    }
  }

  public static function getDadosServicoREST( $post ){
	$objInfraException = new InfraException();

    $urlServico = trim($post['urlServico']);

    if ( !filter_var( $urlServico , FILTER_VALIDATE_URL ) /*|| !InfraUtil::isBolUrlValida( $urlServico , FILTER_VALIDATE_URL )*/ )
      throw new InfraException("Endereço do WebService inválido!");

    if ( $post['tipoWs'] != 'REST' )
      throw new InfraException('O Tipo de Integração informado deve ser do tipo REST.');

    $curl = curl_init( $urlServico );

    curl_setopt_array( $curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_CONNECTTIMEOUT => 8,
	  CURLOPT_TIMEOUT        => 20,
      CURLOPT_CUSTOMREQUEST  => $post['tipoRequisicao'],
    ]);

    // monta dados de parametros necessarios
    if ( array_key_exists( 'parametros' , $post ) ) {
      $payload = json_encode( $post['parametros'] );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
    }

    // monta dados de cabecalho cadastrados
    if( array_key_exists('headers' , $post)){
    	$header = ['Content-Type: application/json'];
    	foreach ( $post['headers'] as $head ) {
    		$vlrConteudo = $head->getStrSinDadoConfidencial() == 'S'
			    ? MdUtlAdmIntegracaoINT::gerenciaDadosRestritos( $head->getStrConteudo() , 'D' )
			    : $head->getStrConteudo();

		    $header[] = $head->getStrAtributo() . ': ' .$vlrConteudo;
    	}
	    curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
    }

    // executa a consulta no webservice
    $ret  = curl_exec( $curl );
    $info = curl_getinfo( $curl );
	$ret  = self::trataRetornoCurl( $info , $ret );

	if ( $info['http_code'] == 0 ) $ret['msg'] = curl_error($curl);

    if ( $ret['suc'] === false ) {
	    $strError = "Falha no serviço SARH \n\n";
	    $strError .= "URL acessada: $urlServico \n\n";
	    $strError .= "STATUS CODE: " . $ret['code'] . "\n\n";
	    $strError .= $ret['msg'];
	    $objInfraException->lancarValidacao($strError);
    	//throw new Exception( $strError );
    } else {
	    curl_close( $curl );
	    return $ret['dados'];
    }
  }

  public static function montarOperacaoREST( $post ){
    $xml = '';

    try {
      $rs = self::getDadosServicoREST( $post );

	    if( empty( $rs) ) throw new InfraException("Não retornou dados para filtro via Json.");

	    if( !$rs->paths ) throw new InfraException("Não existe operação.");

      $xml .= "<operacoes>\n";
      $xml .= "<success>true</success>\n";
      $xml .= "<json>". self::filtrarJSON( $rs ) ."</json>\n";
      $cont = 1;
        
      foreach ( $rs->paths as $key => $value ) {
        $xml .= "<operacao key='{$cont}'>". $key ."</operacao>\n";
        $cont++;
      }
      $xml .= '</operacoes>';
      return $xml;    

    } catch ( Exception $e ) {
      throw new InfraException("Erro Operação REST: {$e->getMessage()}",$e);
    }    
  }

  private static function filtrarJSON( $arrJson ){
    $arrDados = [];

    foreach ( $arrJson as $k => $v ) {
      if ( $k == 'paths' ){
        foreach ( $v as $k1 => $v1 ) {
          //monta dados de entrada
          $arrItem = (array) $v1->post->parameters[0]->schema; 
          $arrItem = explode( '/' , $arrItem['$ref'] );
          $strAcao = end( $arrItem );
          $arrDados['operacoes'][$k1]['parametros']['entrada']['nome'] = $strAcao;
          
          $arrDD = (array) $arrJson->definitions;
          foreach ( $arrDD as $k2 => $v2 ) {
            if( $k2 == $strAcao ){
              $arrParam = (array) $v2->properties;
              foreach ( $arrParam as $k3 => $v3 ) {
                $arrDados['operacoes'][$k1]['parametros']['entrada']['valores'][] = $k3;
              }
            }
          }

          //monta dados de saida
          $arrItem = (array) $v1->post->responses->{200}->schema;
          if( empty( $arrItem['$ref'] ) ) $arrItem = (array) $v1->post->responses->{200}->schema->items;
          $arrItem = explode( '/' , $arrItem['$ref'] );
          $strAcao = end( $arrItem );
          $arrDados['operacoes'][$k1]['parametros']['saida']['nome'] = $strAcao;

          $arrDD = (array) $arrJson->definitions;
          foreach ( $arrDD as $k2 => $v2 ) {
            if( $k2 == $strAcao ){
              $arrParam = (array) $v2->properties;
              foreach ( $arrParam as $k3 => $v3 ) {
                $arrDados['operacoes'][$k1]['parametros']['saida']['valores'][] = $k3;
              }
            }
          }
        }
      }
    }
    return json_encode( $arrDados );
  }

  private static function trataRetornoCurl( $info , $ret ){
  	$arrRet = ['suc' => false , 'msg' => null , 'dados' => null , 'code' => $info['http_code']];
  	$type   = gettype( $ret );
	  $rs     = json_decode( $ret );

	  switch ( $info['http_code'] ) {
		  case 200:
		  	$arrRet['suc']   = true;
		  	$arrRet['dados'] = $rs;
		  	break;

		  case 404:
			  $arrRet['msg'] = MdUtlMensagemINT::$MSG_UTL_135;
			  break;

		  case 500:
		  	if ( $type == 'string' && is_object( $rs ) ) {
		  		$arrRet['msg'] = !is_null($rs->message) ? $rs->message : ( !is_null($rs->error) ? $rs->error : MdUtlMensagemINT::$MSG_UTL_133 );
			  } elseif ( $type == 'boolean') {
		  		$arrRet['msg'] = MdUtlMensagemINT::$MSG_UTL_134;
			  } else {
				  $arrRet['msg'] = MdUtlMensagemINT::$MSG_UTL_133;
			  }
		  	break;

		  default:
			  $arrRet['msg'] = 'Falha não Identificada';
		  	break;
	  }

	  return $arrRet;
  }

  public static function montaParametrosEntrada( $arrObjsIntegracao , $arrParams ){
	  $arrObjsParametros = $arrObjsIntegracao['parametros-integracao'];
	  $arrRetornoParams = [];
	  foreach ( $arrObjsParametros as $parametro ) {
	  	if ( $parametro->getStrTpParametro() == 'E' ) {
	  		foreach ( $arrParams as $k => $v ) {
	  			if ( $parametro->getStrIdentificador() == $k ) {
					  $arrRetornoParams[$parametro->getStrNomeCampo()] = $v;
				  } else if ( $parametro->getStrIdentificador() == MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES['token']
					  &&
					  !empty( $parametro->getStrNomeCampo() )
				  ) {
					    $arrRetornoParams[$parametro->getStrNomeCampo()] = self::gerenciaDadosRestritos(
					    	  $arrObjsIntegracao['integracao']->getStrTokenAutenticacao() , 'D'
					    );
				  }
			  }
		  }
	  }
	  return $arrRetornoParams;
	}

	public static function montaParametrosSaida( $arrObjsParametros ){
		$arrRetorno = [];
		foreach ( $arrObjsParametros as $parametro ) {
			if ( $parametro->getStrTpParametro() == 'S' && in_array($parametro->getStrIdentificador(), MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES) ) {
				$arrRetorno[$parametro->getStrIdentificador()] = $parametro->getStrNomeCampo();
			}
		}
		return $arrRetorno;
	}

	/* *********************************************************************************
		Executa consulta no Webservice e retorna os dados no formato json
	********************************************************************************** */

	public static function executarConsultaREST( $arrObjIntegracao , $parametros = [] ){

		if( $arrObjIntegracao['integracao']->getStrTipoIntegracao() != 'RE' ) throw new InfraException('Execução somente via REST.');

		$strEnderecoServico =  $arrObjIntegracao['integracao']->getStrOperacaoWsdl();

		$params = [
			'urlServico'     => $strEnderecoServico,
			'tipoWs'         => $arrObjIntegracao['integracao']->getStrTipoIntegracao() == 'RE' ? 'REST' : 'SOAP',
			'tipoRequisicao' => MdUtlAdmIntegracaoINT::montarSelectMetdoRequisicao(null, $arrObjIntegracao['integracao']->getNumMetodoRequisicao()),
		];

		//$parametros => array de parametros para consulta no ws
		if( !empty( $parametros ) )	$params['parametros'] = $parametros;
		if( !empty( $arrObjIntegracao['headers-integracao'] ) ) $params['headers'] = $arrObjIntegracao['headers-integracao'];

		$dados = self::getDadosServicoREST( $params );

		return empty( $dados) ? [] : $dados;
	}

  /* *********************************************************************************
    Monta as combos do Mapeamento da Integração
  ********************************************************************************** */
	public static function getDadosFuncionalidade(){
		return [
			MdUtlAdmIntegracaoRN::$CHEFIA   => MdUtlAdmIntegracaoRN::$STR_CHEFIA,
			MdUtlAdmIntegracaoRN::$AUSENCIA => MdUtlAdmIntegracaoRN::$STR_AUSENCIA,
		];
	}

  public static function montarSelectFuncionalidade($itemSelecionado = null , $retornaItem = false, $arrItensCadastrados = null){

		$arrFuncionalidades = self::getDadosFuncionalidade();

    if ( $retornaItem ) return $arrFuncionalidades[$retornaItem];

    $strOptions = '<option value="">Selecione</option>';

    foreach ( $arrFuncionalidades as $k => $v ) {
	    $selected = '';
	    // Filtro para retirar a Funcionalidade que já está cadastrada e ativa
	    if ( !empty($arrItensCadastrados) ){
		    if( !in_array($k,$arrItensCadastrados) ) {
			    if ($itemSelecionado && $itemSelecionado == $k) $selected = ' selected';
			    $strOptions .= "<option value='$k'$selected>$v</option>";
		    }
	    } else {
		    if ( $itemSelecionado && $itemSelecionado == $k ) $selected = ' selected';
		    $strOptions .= "<option value='$k'$selected>$v</option>";
	    }
    }
    return $strOptions;
  }

  public static function montarSelectMetdoAutenticacao($itemSelecionado = null , $retornaItem = false){
    $arrMetAut = [
      MdUtlAdmIntegracaoRN::$AUT_VAZIA        => MdUtlAdmIntegracaoRN::$STR_AUT_VAZIA,
      MdUtlAdmIntegracaoRN::$AUT_HEADER_TOKEN => MdUtlAdmIntegracaoRN::$STR_AUT_HEADER_TOKEN,
      MdUtlAdmIntegracaoRN::$AUT_BODY_TOKEN   => MdUtlAdmIntegracaoRN::$STR_AUT_BODY_TOKEN,
    ];

    if ( $retornaItem ) return $arrMetAut[$retornaItem];

    $strOptions = '<option value="">Selecione</option>';

    foreach ( $arrMetAut as $k => $v ) {
      $selected = '';
      if ( $itemSelecionado && $itemSelecionado == $k ) $selected = 'selected';
      $strOptions .= "<option value='$k' $selected>$v</option>";
    }
    return $strOptions;
  }

  public static function montarSelectMetdoRequisicao($itemSelecionado = null , $retornaItem = false){
    $arrMetReq = [
	    MdUtlAdmIntegracaoRN::$REQUISICAO_POST => MdUtlAdmIntegracaoRN::$STR_REQUISICAO_POST,
	    MdUtlAdmIntegracaoRN::$REQUISICAO_GET  => MdUtlAdmIntegracaoRN::$STR_REQUISICAO_GET,
    ];

    if ( $retornaItem ) return $arrMetReq[$retornaItem];

    $strOptions = '<option value="">Selecione</option>';

    foreach ( $arrMetReq as $k => $v ) {
      $selected = '';
      if ( $itemSelecionado && $itemSelecionado == $k ) $selected = 'selected';
      $strOptions .= "<option value='$k' $selected>$v</option>";
    }
    return $strOptions;
  }

  public static function montarSelectFormato($itemSelecionado = null , $retornaItem = false){
    $arrFormato = [
    	MdUtlAdmIntegracaoRN::$FORMATO_JSON => MdUtlAdmIntegracaoRN::$STR_FORMATO_JSON,
	    MdUtlAdmIntegracaoRN::$FORMATO_XML  => MdUtlAdmIntegracaoRN::$STR_FORMATO_XML,
    ];

    if ( $retornaItem ) return $arrFormato[$retornaItem];

    $strOptions = '<option value="">Selecione</option>';

    foreach ( $arrFormato as $k => $v ) {
      $selected = '';
      if ( $itemSelecionado && $itemSelecionado == $k ) $selected = 'selected';
      $strOptions .= "<option value='$k' $selected>$v</option>";
    }
    return $strOptions;
  }

	/*
	 * GERENCIA OS DADOS DE SAIDA DE ACORDO COM A FUNCIONALIDADE SELECIONADA
	 * */
  private function criaArrayDadosSaida( $funcionalidade ){
  	$arrIdentificador = MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES;
		switch ( $funcionalidade ){
			case MdUtlAdmIntegracaoRN::$CHEFIA:
				return [ 1 => ['Login da Chefia Imediata',$arrIdentificador['loginUsuario']],
								      ['Titularidade da Chefia Imediata',$arrIdentificador['tipoEmpregado']],
						          ['Data Inicial do Exercício',$arrIdentificador['dataInicial']],
								      ['Data Final do Exercício',$arrIdentificador['dataFinal']],
				];
				break;

			case MdUtlAdmIntegracaoRN::$AUSENCIA:
				return [ 1 => ['Identificação Membro Participante',$arrIdentificador['loginUsuario']],
								      ['Sinalizador de Meio Expediente',$arrIdentificador['meioExpediente']],
											['Data Inicial da Ausência',$arrIdentificador['dataInicial']],
								      ['Data Final da Ausência',$arrIdentificador['dataFinal']],
				];
				break;
		}
  }

  public static function geraDadosSaida( $arrDados = null , $tpFuncionalidade = null ){
		$arrFuncionalidades = self::getDadosFuncionalidade();
	  $strSaida           = "";

		foreach ( $arrFuncionalidades as $k_func => $v_func ) {
			$strNmId      = "dadosSaida{$k_func}[]";
			$strIdent     = "identificadorSaida{$k_func}[]";
			$arrDescricao = self::criaArrayDadosSaida( $k_func );
			$strBody      = "";

			foreach ( $arrDescricao as $k => $vl ) {
				$v        = $vl[0];
				$ident    = $vl[1];
				$cmpObrig = '';

				if ( strpos( $v , '*') !== false ) {
					$cmpObrig = 'obrigatorio'.$k_func;
					$v = substr($v,1);
				}

				$option = "";
				if (!empty($arrDados)) {
					foreach ($arrDados as $dado) {
						if ($dado->getStrNome() == $v && $dado->getStrTpParametro() == 'S') {
							$option = "<option selected>{$dado->getStrNomeCampo()}</option>";
						}
					}
				}
				$lblObg = !empty($cmpObrig) ? ' class="infraLabelObrigatorio"' : ' class="infraLabelOpcional"';
				$class  = implode('_',explode(' ',$v));
				$strBody .= "<tr>
	                     <td>
	                       <label ".$lblObg.">$v</label>
	                       <input type='hidden' name='$strNmId' value='$v' dataform='saida$k_func'>
	                       <input type='hidden' name='$strIdent' value='$ident' dataform='saida$k_func'>
	                     </td>
	                     <td>
	                       <select name='sel" . ucfirst($strNmId) . "' class='infraSelect form-control $cmpObrig' dataform='saida$k_func'>
	                         <option value=''>Selecione</option>
	                         $option
	                       </select>
	                     </td>
	                  </tr>";
			}

			$displayDiv = ( !empty( $tpFuncionalidade ) && $tpFuncionalidade == $k_func ) ? '' : 'style="display: none;"';

			$strSaida .= '<div class="row mb-2 dvDadosSaida" id="dvDadosSaida'.$k_func.'" '.$displayDiv.'>
									    <div class="col-12">
									      <table class="infraTable table" id="tbDadosSaida'.$k_func.'" summary="sumDadosSaida">
									      <caption class="infraCaption">&nbsp;</caption>
									        <thead>
									          <tr>
									            <th class="infraTh" style="width:50%;">Campos de Destino SEI</th>
									            <th class="infraTh" style="width:50%;">Dados de Saída do WebService</th>
									          </tr>
									        </thead>
									        <tbody>
									        	'.$strBody.'
									        </tbody>
									      </table>
									    </div>
									  </div>';
		}
    return $strSaida;
  }

	/*
	 * GERENCIA OS DADOS DE ENTRADA DE ACORDO COM A FUNCIONALIDADE SELECIONADA
	 * */
	private function criaArrayDadosEntrada( $funcionalidade ){
		$arrIdentificador = MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES;
		switch ( $funcionalidade ){
			case MdUtlAdmIntegracaoRN::$CHEFIA:
				return [ 1 => ['Token',$arrIdentificador['token']],
											['Conteúdo de Autenticação',$arrIdentificador['conteudoAutenticacao']],
								      ['Login da Chefia Imediata',$arrIdentificador['loginUsuario']]
				];
				break;

			case MdUtlAdmIntegracaoRN::$AUSENCIA:
				return [ 1 => ['Token',$arrIdentificador['token']],
											['Conteúdo de Autenticação',$arrIdentificador['conteudoAutenticacao']],
										  ['Identificação Membro Participante',$arrIdentificador['loginUsuario']],
											['Data Inicial da Ausência',$arrIdentificador['dataInicial']],
											['Data Final da Ausência',$arrIdentificador['dataFinal']],
				];
				break;
		}
	}

  public static function geraDadosEntrada( $arrDados = null , $metAutenticacao = null , $tpFuncionalidade = null , $objMdUtlAdmIntegracaoDTO = null){
	  $arrFuncionalidades = self::getDadosFuncionalidade();
	  $strSaida           = "";
		$token              = $objMdUtlAdmIntegracaoDTO->isSetStrTokenAutenticacao()
														? MdUtlAdmIntegracaoINT::gerenciaDadosRestritos($objMdUtlAdmIntegracaoDTO->getStrTokenAutenticacao() , 'D')
														: null;
	  $tokenCamuflado     = empty($arrDados) ? null : MdUtlAdmIntegracaoRN::$INFO_RESTRITO;

	  foreach ( $arrFuncionalidades as $k_func => $v_func ) {
		  $strNmId      = "dadosEntrada{$k_func}[]";
		  $strIdent     = "identificadorEntrada{$k_func}[]";
		  $contAut      = "txtTokenAut{$k_func}";
		  $contAutHdn   = "hdnTokenAut{$k_func}";
		  $idContAut    = "rowTokenAut{$k_func}";
		  $strBody      = "";
		  $arrDescricao = self::criaArrayDadosEntrada($k_func);

		  foreach ( $arrDescricao as $k => $vl ) {
		  	$v        = $vl[0];
		  	$ident    = $vl[1];
			  $cmpObrig = '';

			  if ( strpos( $v , '*') !== false ) {
			  	$cmpObrig = 'obrigatorio'.$k_func;
				  $v = substr($v,1);
			  }

			  $option      = "";
			  $displayNone = "";

			  if( !empty($metAutenticacao) ){ // alterar ou consultar
				  if( $vl[1] == MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES['token'] ) $displayNone = empty($token) ? "display:none" : "";
			  } else { // cadastrar
			  	if ( $vl[1] == MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES['token'] ) $displayNone = "display:none";
			  }

			  if (!empty($arrDados)) {
				  foreach ($arrDados as $dado) {
					  if ($dado->getStrNome() == $v && $dado->getStrTpParametro() == 'E') $option = "<option selected>{$dado->getStrNomeCampo()}</option>";
				  }
			  }

			  $lblObg = !empty($cmpObrig) ? ' class="infraLabelObrigatorio"' : ' class="infraLabelOpcional"';
			  $class  = $vl[1];

			  if ( $vl[1] == MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES['conteudoAutenticacao'] ) {
			  	$displayToken = empty($token) ? "display:none": "";

			  	$strBody .= "<tr class='$class' id='$idContAut' style='$displayToken'>
                          <td>
                            <label class='infraLabelOpcional'>$v</label>
                          </td>
                          <td>
                          	<input type='text' id='$contAut' name='$contAut' class='infraText form-control' value='$tokenCamuflado'
                                onkeypress='return infraMascaraTexto(this,event,76);' maxlength='76' dataform='entrada$k_func'
                                tabindex='".PaginaSEI::getInstance()->getProxTabDados()."'/>
                                
                            <input type='hidden' id='$contAutHdn' name='$contAutHdn'value='$token' dataform='entrada$k_func' />
													</td>
											</tr>";
			  } else {
				  $strBody .= "<tr class='$class' style='$displayNone'>
		                     <td>
		                       <label $lblObg>$v</label>
		                       <input type='hidden' name='$strNmId' value='$v' dataform='entrada$k_func'>
		                       <input type='hidden' name='$strIdent' value='$ident' dataform='entrada$k_func'>
		                     </td>
		                     <td>
		                       <select name='sel" . ucfirst($strNmId) . "' class='infraSelect form-control $cmpObrig' dataform='entrada$k_func'>
		                         <option value=''>Selecione</option>
		                         $option
		                       </select>
		                     </td>
		                  </tr>";
			  }
		  }

		  $displayDiv = ( !empty( $tpFuncionalidade ) && $tpFuncionalidade == $k_func ) ? '' : 'style="display: none;"';

		  $strSaida .= '<div class="row mb-2 dvDadosEntrada" id="dvDadosEntrada'.$k_func.'" '.$displayDiv.'>
									    <div class="col-12">
									      <table class="infraTable table" id="tbDadosEntrada'.$k_func.'" summary="sumDadosEntrada">
									      <caption class="infraCaption">&nbsp;</caption>
									        <thead>
									          <tr>
									            <th class="infraTh" style="width:50%;">Campos de Origem SEI</th>
									            <th class="infraTh" style="width:50%;">Dados de Entrada no WebService</th>
									          </tr>
									        </thead>
									        <tbody>
									          '.$strBody.'
									        </tbody> 
									      </table>
									    </div>
									  </div>';
	  }
    return $strSaida;
  }

  public static function geraOperacao ( $operacao = null ){
    if ( !empty( $operacao ) ) {
    	$arrOperacao = explode('/',$operacao);
    	return end($arrOperacao);
    }
    return "";
  }

  public static function gerenciaDadosRestritos($valor, $acao = 'C'){
    switch ( $acao ) {
		  case 'C':
			  return base64_encode( strrev( base64_encode( strrev( $valor ) ) ) );
			  break;

		  case 'D':
			  return strrev( base64_decode( strrev( base64_decode( $valor ) ) ) );
			  break;

		  default:
		    throw new InfraException('Tipo de Ação não declarado na função.');
	  }
  }
}
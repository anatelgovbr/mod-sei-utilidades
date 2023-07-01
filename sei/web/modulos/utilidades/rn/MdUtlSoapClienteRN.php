<?
/*
    Adicionado por Gustavo Camelo - gustavos.colab
    01/12/2022
*/

require_once dirname(__FILE__).'/../../../SEI.php';
require_once dirname(__FILE__).'/../lib/nusoap/nusoap.php';

class MdUtlSoapClienteRN extends nusoap_client{

	protected $wsdl;
    protected $soapVersion = SOAP_1_2;
	protected $options;
	
	function __construct($endpoint,$wsdl = false,$proxyhost = false,$proxyport = false,$proxyusername = false, $proxypassword = false, $timeout = 0, $response_timeout = 30, $portName = ''){
        ini_set('default_socket_timeout', 6000);
        ini_set("soap.wsdl_cache_enabled", "0");

        $this->wsdl = $wsdl;
        parent::nusoap_client($endpoint,$wsdl,$proxyhost,$proxyport,$proxyusername, $proxypassword, $timeout, $response_timeout, $portName);
    }

    public function getFunctions(){
        $functions = array();

        if ($this->endpointType == 'wsdl' && is_null($this->wsdl)) {
            $this->loadWSDL();
            if ($this->getError())
            return false;
        }
        //escrevendo nome de cada operaçao disponivel
        foreach($this->operations as $op){
        $functions[] =  $op['name']; //nome da operaçao
        }
        return $functions;
    }

    /**
     * Sobreescrita do metodo da biblioteca nusoap para permitir a parametrização da versão do SOAP (1.2 e 1.1),
     * metodo sobreescrito pois quando o WSLD atende tanto soap1.1 quanto soap1.2 por defalt caia no 1.1 entao
     * para não impactar na biblioteca do componetente do nusoap
     *
     * @see checkWSDL() - sei/web/modulos/litigioso/lib/nusoap/nusoap.php
     */
    public function checkWSDL() {
        $this->appendDebug($this->wsdl->getDebug());
        $this->wsdl->clearDebug();
        $this->debug('checkWSDL');
        // catch errors
        if ($errstr = $this->wsdl->getError()) {
            $this->appendDebug($this->wsdl->getDebug());
            $this->wsdl->clearDebug();
            $this->debug('got wsdl error: '.$errstr);
            $this->setError('wsdl error: '.$errstr);
        } elseif ($this->bindingType == 'soap' && $this->operations = $this->wsdl->getOperations($this->portName, 'soap')) {
            $this->appendDebug($this->wsdl->getDebug());
            $this->wsdl->clearDebug();
            $this->bindingType = 'soap';
            $this->debug('got '.count($this->operations).' operations from wsdl '.$this->wsdlFile.' for binding type '.$this->bindingType);
        } elseif ($this->bindingType == 'soap12' && $this->operations = $this->wsdl->getOperations($this->portName, 'soap12')) {
            $this->appendDebug($this->wsdl->getDebug());
            $this->wsdl->clearDebug();
            $this->bindingType = 'soap12';
            $this->debug('got '.count($this->operations).' operations from wsdl '.$this->wsdlFile.' for binding type '.$this->bindingType);
            $this->debug('**************** WARNING: SOAP 1.2 BINDING *****************');
        } else {
            $this->appendDebug($this->wsdl->getDebug());
            $this->wsdl->clearDebug();
            $this->debug('getOperations returned false');
            $this->setError('no operations defined in the WSDL document!');
        }
    }

    public function getParamsInput($nameOperations, $recursivo = false)
    {
        $operations = $this->getOperationData($nameOperations);
        $complexTypes = $this->wsdl->schemas[$this->wsdl->namespaces['tns']][0]->complexTypes;
        $outputArr = array();

        if ($recursivo) {
            $returnType = $nameOperations;
        } else {
            if (!$operations) {
                throw new InfraException('Nome da operação não existe ou não encontrada para essa versão SOAP.');
            }

            $nameType = $this->getEntidadePorUrlWSDL($operations['input']['parts']['parameters']);

            if (!$nameType){
                $nameType = key($operations['input']['parts']);
            }

            if (!$complexTypes[$nameType]['elements']) {
                return $outputArr;
            }


            $returnType = current($complexTypes[$nameType]['elements']);
            $returnType = $this->getEntidadePorUrlWSDL($returnType['type']);
            $returnType = $this->_verificaTipoDadosWebService($returnType, $nameType);
        }

        if (!empty($complexTypes[$returnType]['elements'])) {
            $outputArr = $this->pegarElemento($complexTypes[$returnType]);
        }

        return $outputArr;
    }

    /*
     * Verifica se o tipo retornado é um tipo ou realmente o nome.
     * */
    private function _verificaTipoDadosWebService($returnType, $nameType){
        $isTipo = false;
        $arrTipos = array('string', 'boolean', 'long', 'int', 'decimal', 'dateTime', 'short');

       if(in_array($returnType, $arrTipos)){
           $isTipo = true;
       }

        $retorno = $isTipo ? $nameType : $returnType;

        return $retorno;
    }


    public function getParamsOutput($nameOperations){
        $operations     = $this->getOperationData($nameOperations);
        $complexTypes   = $this->wsdl->schemas[$this->wsdl->namespaces['tns']][0]->complexTypes;
        $outputArr     = array();


        if(!$operations)
            throw new InfraException('Nome da operação não existe.');

        /**
         * @todo if para tratar o web-service da ANATEL de serviço aonde o wsdl não possui assinatura de output
         */
        if(empty($operations['output']['parts'])){
            $resp = $this->call($nameOperations, array('soap_version'=>$this->soapVersion,'cache_wsdl' => WSDL_CACHE_NONE));
            if($this->responseData === false){
                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('Não foi possível comunicação com o servidor.');
                $objInfraException->lancarValidacoes();
            }

            if(!$resp){
                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('Não possui resposta do web-service.');
                $objInfraException->lancarValidacoes();
            }

            foreach ($resp['listaTipoServico'][0] as $campo => $valor){
                $outputArr[] = $campo;
            }
            return $outputArr;
        }

        $nameType       = $this->getEntidadePorUrlWSDL($operations['output']['parts']['parameters']);
        if(!$nameType)
            $nameType       = key($operations['output']['parts']);

        $returnType     = $this->getEntidadePorUrlWSDL($complexTypes[$nameType]['elements']['return']['type']);
        if($complexTypes[$returnType]['elements']){
            $outputArr = $this->pegarElemento($complexTypes[$returnType]);
        }
        return $outputArr;
    }

    private function pegarElemento($complexTypes){
        $outputArr = array();
        if(array_key_exists('extensionBase', $complexTypes)){
            $returnType     = $this->getEntidadePorUrlWSDL($complexTypes['extensionBase']);
            $complexTypesGeral   = $this->wsdl->schemas[$this->wsdl->namespaces['tns']][0]->complexTypes;
            if(isset($complexTypesGeral[$returnType])){
                $outputArr = $this->pegarElemento($complexTypesGeral[$returnType]);
            }

        }

        if($complexTypes['elements']){
            foreach ($complexTypes['elements'] as $nome => $elementArr){
                $outputArr[] = $nome;
            }
            sort($outputArr);
        }

        return $outputArr;
    }//SMA

    private function getEntidadePorUrlWSDL($urlWSDL){
        $urlWSDL = strrchr($urlWSDL, ':');
        if(!$urlWSDL) return null;

        return preg_replace('/[^a-z0-9]/i','',$urlWSDL);
    }
    /**
     * xml2array() will convert the given XML text to an array in the XML structure.
     * Link: http://www.bin-co.com/php/scripts/xml2array/
     * Arguments : $contents - The XML text
     *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
     *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
     * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
     * Examples: $array =  xml2array(file_get_contents('feed.xml'));
     *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
     */
    public function xml2array($contents, $get_attributes=1, $priority = 'tag') {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }


    public function enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, $nomeArrPrincipal = false){
        $arrResultado = array();

        try{
            $err = $this->getError();

            if($err){
                throw new InfraException($err);
            }

            $this->soap_defencoding = 'UTF-8';
            $this->decode_utf8 = false;

            //converte todas as entradas de parametro para enviar como UTF-8 e evitar erro de parse no xml e erro no Webservice Server
            $montarParametroEntrada = $this->convertEncondig($montarParametroEntrada, $this->soap_defencoding);

            if($nomeArrPrincipal){
                $montarParametroEntrada = array($nomeArrPrincipal => $montarParametroEntrada);
            }
            $opData = $this->getOperationData($objMdLitIntegracaoDTO->getStrOperacaWsdl());

            if(!empty($opData['endpoint'])){
                //@todo retirar quanto verificar a configuração do wso2 da anatel
                $this->forceEndpoint = str_replace('https', 'http',$opData['endpoint']);
            }

            $this->persistentConnection = false;
            $arrResultado = $this->call($objMdLitIntegracaoDTO->getStrOperacaWsdl(), $montarParametroEntrada);

            $err = $this->getError();

            if($err){

                if($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO){
                    $exception = new InfraException();
                    //tratamento do encode dinamico
                    $err = $this->convertEncondig($err, 'ISO-8859-1');
                    $exception->lancarValidacao('Não foi possível a comunicação com o Webservice da Arrecadação. Contate o Gestor do Controle.', null,new Exception($err));
                }

                InfraDebug::getInstance()->setBolLigado(true);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->limpar();
                InfraDebug::getInstance()->gravar($this->request);
                InfraDebug::getInstance()->gravar('Ocorreu erro ao conectar com a operação('.$objMdLitIntegracaoDTO->getStrOperacaWsdl().').'.$err);

                LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('Ocorreu erro ao conectar com a operação('.$objMdLitIntegracaoDTO->getStrOperacaWsdl().'). '.$err);
                $objInfraException->lancarValidacoes();
            }

        }catch (Exception $e){

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Ocorreu erro ao executar o serviço de lançamento. ', $e );
        }

        if(count($arrResultado) > 0) {
            //converte o retorno do serviço para o encode esperado pelo sei, que por padrao é ISO-8859-1
            return $this->convertEncondig($arrResultado, 'ISO-8859-1');
        }

        return false;
    }

    /**
     * Detecta o encode do array informado
     * @param $arrParams
     * @param string $toEncode
     * @return mixed
     */
    public function convertEncondig(&$params, $toEncode='UTF-8')
    {
        try {
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    if(is_array($value)){
                        $params[$key] = $this->convertEncondig($value, $toEncode);
                        continue;
                    }
                    //detecta o encode que a aplicação esta enviando
                    $fromEncoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
                    //converte para encode informado
                    if ($toEncode != $fromEncoding) {
                        $params[$key] = mb_convert_encoding($value, $toEncode, $fromEncoding);
                    }
                }
            } else {
                $fromEncoding = mb_detect_encoding($params, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
                //converte para encode informado
                if ($toEncode != $fromEncoding) {
                    $params = mb_convert_encoding($params, $toEncode, $fromEncoding);
                }
            }

            return $params;
        } catch (Exception $e){
            $exception = new InfraException();
            $exception->lancarValidacao('Erro ao converter os parametros do webservice. '.$e->getMessage(),null, $e);
        }
    }


    public function enviarDados($strOperacaoWsdl, $montarParametroEntrada, $nomeArrPrincipal = false){
        $arrResultado = array();

        try{
            $err = $this->getError();

            if($err){
                throw new InfraException($err);
            }

            $this->soap_defencoding = 'ISO-8859-1';
            $this->decode_utf8 = false;
            if($nomeArrPrincipal){
                $montarParametroEntrada = array($nomeArrPrincipal => $montarParametroEntrada);
            }
            $opData = $this->getOperationData($strOperacaoWsdl);

            if(!empty($opData['endpoint'])){
                //@todo retirar quanto verificar a configuração do wso2 da anatel
                $this->forceEndpoint = str_replace('https', 'http',$opData['endpoint']);
            }

            $this->persistentConnection = false;
            $arrResultado = $this->call($strOperacaoWsdl, $montarParametroEntrada);

            $err = $this->getError();

            if($err){

                InfraDebug::getInstance()->setBolLigado(true);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->limpar();
                InfraDebug::getInstance()->gravar($this->request);
                InfraDebug::getInstance()->gravar('Ocorreu erro ao conectar com a operação('.$strOperacaoWsdl.').'.$err);

                LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('Ocorreu erro ao conectar com a operação('.$strOperacaoWsdl.'). '.$err);
                $objInfraException->lancarValidacoes();
            }

        }catch (Exception $e){

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Ocorreu erro ao executar o serviço de lançamento. ', $e );
        }

        if(count($arrResultado) > 0) {
            return $arrResultado;
        }

        return false;
    }

    public function setSoapVersion($soapVersion = '1.2')
    {
        $this->soapVersion = SOAP_1_2;
        $this->bindingType = 'soap12';
        if ($soapVersion == '1.1') {
            $this->soapVersion = SOAP_1_1;
            $this->bindingType = 'soap';
        }
    }
}
<?php

class Recrutador_AgendaentrevistaController extends Cityware_Controller_Recrutador {

    private $ind_status_agenda_entrevista = array(  'A' => 'Em Aberto',
                                                    'C' => 'Cancelada',
                                                    'N' => 'Não Compareceu',
                                                    'R' => 'Relizada');

    public function indexAction() {
        $sessaoAgendaEntrevista = 'agendaentrevista';
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/agendaentrevista_index.js');
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $idPessoaJuridica = $this->_session['idPessoaJuridica'];

        $this->view->nomeRecrutador = $this->_session['nomeUsuario'];

        // Antes do post, selecionar automaticamente as vagas da empresa
        $db = $this->getConnection();
        $db->defineSqlSelect('ve.cod_vaga_empresa');
        $db->defineSqlSelect('ve.nom_fantasia_empresa');
        $db->defineSqlFrom('tab_web_vaga_empresa AS ve', 'sistema');
        $db->defineSqlWhere("ve.cod_pessoa_juridica = '{$idPessoaJuridica}'");
        $db->defineSqlWhere("ve.ind_status IN ('A','N')");
        $db->defineSqlOrderBy("ve.nom_fantasia_empresa ASC");
        $db->setDebug(false);
        $rsEmpresa = $db->executeSelectQuery();

        $this->view->rsEmpresa = $rsEmpresa;

        $sessionNamespace = new Zend_Session_Namespace(SESSION_RECRUTADOR);
        $sessionNamespace->setExpirationSeconds(3600);

        $page = $this->_getParam('page', null);

        if (empty($page)) {
            unset($_SESSION[SESSION_RECRUTADOR][$sessaoAgendaEntrevista]);
            $page = 1;
        }

        if (!isset($this->_session[$sessaoAgendaEntrevista])) {
            $sessionNamespace->agendaentrevista = $this->_getAllParams();
            $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        } else {
            $sessionNamespace->agendaentrevista = $this->_getAllParams() + $this->_session[$sessaoAgendaEntrevista];
            $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        }
        $this->_session[$sessaoAgendaEntrevista]['idPessoaJuridica'] = $idPessoaJuridica;

        $db->defineSqlSelect('ae.cod_agenda_entrevista');
        $db->defineSqlSelect('twpsc.cod_curriculo');
        $db->defineSqlSelect('ae.cod_vaga');
        $db->defineSqlSelect('ae.num_telefone');
        $db->defineSqlSelect('ae.dta_entrevista');
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('ae.ind_status_agenda_entrevista');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('ae.num_telefone');
        $db->defineSqlSelect('v.tit_vaga');
        $db->defineSqlSelect('v.dta_cadastro');
        $db->defineSqlSelect('ve.cod_vaga_empresa');
        $db->defineSqlSelect('ve.nom_fantasia_empresa');
        $db->defineSqlFrom('tab_web_agenda_entrevista AS ae', 'sistema');
        $db->defineSqlJoinUsing('tab_web_proc_sel_curriculo AS twpsc', 'twpsc.cod_proc_sel_curriculo = ae.cod_proc_sel_curriculo', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa_rel AS ver', 'ver.cod_vaga = ae.cod_vaga', 'LEFTJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa AS ve', 've.cod_vaga_empresa = ver.cod_vaga_empresa', 'LEFTJOIN', 'sistema');
        $db->defineSqlWhere("ae.cod_pessoa_juridica = '{$idPessoaJuridica}'");

        // Leitura de parâmetros
        //empresa/68/vaga/263/status/A/candidato/2102/datini/01-01-2014/datfim/29-02-2014/
        $parametros = array();
        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_vaga'] = $this->_getParam('vaga', null);
        $parametros['ind_status_agenda_entrevista'] = $this->_getParam('status', null);
        $parametros['cod_pessoa_fisica'] = $this->_getParam('candidato', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);

        if (isset($parametros['dta_inicial'])){
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])){
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }

        // Empresa
        if (isset($parametros['cod_vaga_empresa']) &&
           !(empty($parametros['cod_vaga_empresa']))) {
            $db->defineSqlWhere("ve.cod_vaga_empresa = '{$parametros['cod_vaga_empresa']}'");
        }
        // Vaga
        if (isset($parametros['cod_vaga']) && !(empty($parametros['cod_vaga']))) {
            $db->defineSqlWhere("ae.cod_vaga = '{$parametros['cod_vaga']}'");
        }
        // Data inicial
        if (isset($parametros['dta_inicial']) && !(empty($parametros['dta_inicial']))) {
            try {
                $dta_inicial = Cityware_Format_Mask::fieldMask($parametros['dta_inicial'], 'date', 'Y-m-d');
                $db->defineSqlWhere("ae.dta_entrevista >= '{$dta_inicial}'");
            } catch (Exception $e) {
                unset($parametros['dta_inicial']);
            }
        } else {
            //$_SESSION[SESSION_RECRUTADOR][$sessaoAgendaEntrevista]['dta_inicial'] = null;
        }
        // Data final
        if (isset($parametros['dta_final']) && !(empty($parametros['dta_final']))) {
            try {
                $dta_final = Cityware_Format_Mask::fieldMask($parametros['dta_final'], 'date', 'Y-m-d 23:59:59');
                $db->defineSqlWhere("ae.dta_entrevista <= '{$dta_final}'");
            } catch(Exception $e) {
                unset($parametros['dta_final']);
            }
        } else {
            //$_SESSION[SESSION_RECRUTADOR][$sessaoAgendaEntrevista]['dta_final'] = null;
        }
        // Pessoa Física
        if (isset($parametros['cod_pessoa_fisica']) && !(empty($parametros['cod_pessoa_fisica']))) {
            $db->defineSqlWhere("ae.cod_pessoa_fisica = '{$parametros['cod_pessoa_fisica']}'");
        }
        // Status
        if (isset($parametros['ind_status_agenda_entrevista']) && !(empty($parametros['ind_status_agenda_entrevista']))) {
            $db->defineSqlWhere("ae.ind_status_agenda_entrevista = '{$parametros['ind_status_agenda_entrevista']}'");
        }
        $db->defineSqlOrderBy('pf.nom_pessoa ASC');

        $db->setDebug(false);
        $rsListagem = $db->executeSelectQuery(true, $page, 10);

        foreach ($rsListagem['db'] as $key => $value) {
            $rsListagem['db'][$key]['des_status_agenda_entrevista'] = $this->ind_status_agenda_entrevista[ $rsListagem['db'][$key]['ind_status_agenda_entrevista'] ];
        }

        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsVaga = $this->buscaVagas($cod_vaga_empresa);
            $this->view->rsCandidato = $this->buscaCandidatos($cod_vaga_empresa);
        }
    }

    public function editarAction() {
        $this->setHeadCssLink(URL_DEFAULT . 'css/recrutador/datepicker.css');
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/agendaentrevista_editar.js');
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);


        $cod_agenda_entrevista = $this->_getParam('id');

        //Dados de Agenda Entrevista
        $db = $this->getConnection();
        $db->defineSqlSelect('ae.cod_agenda_entrevista');
        $db->defineSqlSelect('ae.cod_vaga');
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('ae.dta_entrevista');
        $db->defineSqlSelect('ae.cod_vaga');
        $db->defineSqlSelect('v.tit_vaga');
        $db->defineSqlSelect('ae.nom_contato');
        $db->defineSqlSelect('ae.des_email_contato');
        $db->defineSqlSelect('ae.num_telefone');
        $db->defineSqlSelect('ae.des_outros_dados');
        $db->defineSqlSelect('ae.des_endereco_informacoes');
        $db->defineSqlSelect('ae.ind_status_agenda_entrevista');
        $db->defineSqlFrom("tab_web_agenda_entrevista AS ae", 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("ae.ind_status IN ('A','N')");
        $db->defineSqlWhere("ae.cod_agenda_entrevista = '{$cod_agenda_entrevista}'");
        $db->setDebug(false);

        $rsAgenda = $db->executeSelectQuery();
        $rsAgenda[0]['dta_entrevista_date'] = Cityware_Format_Mask::fieldMask($rsAgenda[0]['dta_entrevista'], 'DATE', 'd/m/Y');
        $rsAgenda[0]['dta_entrevista_time'] = Cityware_Format_Mask::fieldMask($rsAgenda[0]['dta_entrevista'], 'TIME', 'H:i');
        $dta_entrevista = empty($rsAgenda[0]['dta_entrevista']) ? '' : $rsAgenda[0]['dta_entrevista'];

        $this->view->rsAgenda = $rsAgenda;
        $cod_vaga = empty($rsAgenda[0]['cod_vaga']) ? -1 : $rsAgenda[0]['cod_vaga'];
        //$cod_pessoa_fisica = empty($rsAgenda[0]['cod_pessoa_fisica']) ? -1 : $rsAgenda[0]['cod_pessoa_fisica'];
        $nom_pessoa = empty($rsAgenda[0]['nom_pessoa']) ? '' : $rsAgenda[0]['nom_pessoa'];
        $tit_vaga = empty($rsAgenda[0]['tit_vaga']) ? '' : $rsAgenda[0]['tit_vaga'];

        $this->view->assign('nom_pessoa', $nom_pessoa);
        $this->view->assign('tit_vaga', $tit_vaga);
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Entrevistas desta vaga
        $db->defineSqlSelect('aa.cod_vaga');
        $db->defineSqlSelect('aa.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('aa.dta_entrevista');
        $db->defineSqlFrom("tab_web_agenda_entrevista AS aa", 'sistema');
        //$db->defineSqlJoinUsing('tab_web_pessoa AS twc','twc.cod_curso = twvc.cod_curso','INNERJOIN','sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = aa.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("aa.ind_status  = 'A'");
        $db->defineSqlWhere("aa.cod_vaga = '{$cod_vaga}'");
        $db->defineSqlWhere("aa.cod_agenda_entrevista <> '{$cod_agenda_entrevista}'");
        $db->defineSqlOrderBy("aa.dta_entrevista ASC");
        $db->setDebug(false);
        $rsEntrevistas = $db->executeSelectQuery();

        $evento_outros = Array();
        foreach ($rsEntrevistas as $key => $value) {
            $evento_outros[date_format(date_create($value['dta_entrevista']), 'n/j/Y')] = Array('nome' => $value['nom_pessoa']);
        }
        $this->setHeadJsScript('window.evento_outros = ' . json_encode($evento_outros), 'text/javascript');

        $dta_entrevista_date = date_format(date_create($dta_entrevista), 'd/m/Y');

        $evento = Array();
        $evento[date_format(date_create($dta_entrevista), 'n/j/Y')] = Array('nome' => $nom_pessoa);
        $this->setHeadJsScript('window.evento = ' . json_encode($evento), 'text/javascript');
        $this->setHeadJsScript('window.dta_entrevista_date = ' . json_encode($dta_entrevista_date), 'text/javascript');

        $validateRealTime = array();

        // Instancia o adapter do formulário
        $forms = Cityware_Form::factory('zend');
        $forms->setFormIni(array('formName' => 'editar'));
        $this->view->formVal = $rsAgenda[0];

        // Verifica se foi enviado dados pelo formulário
        if ($this->_request->isPost()) {

            $data_entrevista_valida = true;
            $this->view->debug = '';

            // Envia os dados do formulário para o construtor de formulário
            $forms->setPopulateParams($_POST);
            // Monta o formulário com os dados para serem validados
            $varObjectForm = $forms->formBuider($validateRealTime);

            $this->view->varObjectForm = $varObjectForm;

            // Valida dados enviado pelo formulário
            $ok = false;
            if ($varObjectForm->isValid($_POST)) {
                try {
                    //$dta_entrevista = Cityware_Format_Mask::fieldMask($this->_getParam('dta_entrevista'), 'DATE', 'Y-m-d');
                    $dta_entrevista_date_new = $this->_getParam('dta_entrevista_date') . ' ' . $this->_getParam('dta_entrevista_time');
                    $dt = DateTime::createFromFormat('d/m/Y H:i', $dta_entrevista_date_new);
                    $dta_entrevista_date_new = $dt->format('Y-m-d H:i:s');
                    $db->defineSqlUpdate('ind_status_agenda_entrevista', $this->_getParam('ind_status_agenda_entrevista'));
                    $db->defineSqlUpdate('des_endereco_informacoes', $this->_getParam('des_endereco_informacoes'));
                    $db->defineSqlUpdate('des_outros_dados', $this->_getParam('des_outros_dados'));
                    $db->defineSqlUpdate('dta_entrevista', $dta_entrevista_date_new);
                    $db->defineSqlFrom('tab_web_agenda_entrevista', 'sistema');
                    $db->defineSqlWhere("cod_agenda_entrevista = '{$cod_agenda_entrevista}'");
                    $db->setDebug(false);
                    $db->executeUpdateQuery();
                    $ok = true;
                } catch (Exception $exc) {
                    $db->defineCloseConnection();
                    throw new Exception('Erro ao tentar salvar agendamento de entrevista - ' . $exc->getMessage(), 500);
                }
                if ($ok === true){
                    $this->enviarEmail($cod_agenda_entrevista);
                    $this->setHeadJsScript("fancyAlert('E-mail enviado com sucesso!', 'suc', '');", 'text/javascript');
                }
                $this->_redirect(LINK_DEFAULT . 'recrutador/agendaentrevista/index/msg/sucess');
            } else {
                foreach ($varObjectForm->getErrors() as $key => $value) {
                    if (!empty($value)) {
                        $errosForm[$key] = $value;
                    }
                }

                $arrayMessages = $varObjectForm->getMessages();

                foreach ($arrayMessages as $key => $value) {
                    if (!empty($arrayMessages[$key]['emailAddressInvalidFormat'])) {
                        $arrayMessages[$key]['emailAddressInvalidFormat'] = 'O e-mail informado é inválido.';
                    }
                    if (!empty($arrayMessages[$key]['emailAddressInvalidHostname'])) {
                        $arrayMessages[$key]['emailAddressInvalidHostname'] = 'O e-mail informado é inválido.';
                    }
                    if (!empty($arrayMessages[$key]['hostnameInvalidHostname'])) {
                        $arrayMessages[$key]['hostnameInvalidHostname'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameLocalNameNotAllowed'])) {
                        $arrayMessages[$key]['hostnameLocalNameNotAllowed'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameUndecipherableTld'])) {
                        $arrayMessages[$key]['hostnameUndecipherableTld'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameUnknownTld'])) {
                        $arrayMessages[$key]['hostnameUnknownTld'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameInvalidLocalName'])) {
                        $arrayMessages[$key]['hostnameInvalidLocalName'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressInvalidLocalPart']) or !empty($arrayMessages[$key]['emailAddressDotAtom']) or !empty($arrayMessages[$key]['emailAddressInvalidLocalPart'])) {
                        $arrayMessages[$key]['emailAddressInvalidFormat'] = 'O e-mail informado é inválido.';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressDotAtom'])) {
                        $arrayMessages[$key]['emailAddressDotAtom'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressQuotedString'])) {
                        $arrayMessages[$key]['emailAddressQuotedString'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressInvalidLocalPart'])) {
                        $arrayMessages[$key]['emailAddressInvalidLocalPart'] = '';
                    }
                    if (!empty($arrayMessages[$key]['missingToken'])) {
                        $arrayMessages[$key]['missingToken'] = 'O e-mail que foi confirmado está diferente do informado.';
                    }
                    if (!empty($arrayMessages[$key]['notSame'])) {
                        $arrayMessages[$key]['notSame'] = 'A senha que foi confirmada está diferente da informada.';
                    }
                }

                // Envia formulário para a camada de visão
                $this->view->formVal = $this->getRequest()->getPost();
                $this->view->form = $varObjectForm->getErrorMessages();
                $this->view->formErros = $errosForm;
                $this->view->formErrosMsg = $arrayMessages;

                $this->setHeadJsScript("fancyAlert('Existem erros de preenchimento no seu cadastro. <br/>Gentileza conferir e corrigir todos os campos destacados em vermelho', 'err', '');", 'text/javascript');
            }
        } else {

        } // not post
    }

    public function novoAction() {
        // O id refere-se a cod_proc_sel_curriculo
        $this->view->popup = true;

        $this->setHeadCssLink(URL_DEFAULT . 'css/recrutador/datepicker.css');
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/agendaentrevista_editar.js');
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $db = $this->getConnection();

        $db->defineSqlSelect('psc.cod_proc_sel_curriculo');
        $db->defineSqlSelect('ps.cod_processo_seletivo');
        $db->defineSqlSelect('c.cod_curriculo');
        $db->defineSqlSelect('pf.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('ps.cod_vaga');
        $db->defineSqlSelect('v.tit_vaga');
        $db->defineSqlSelect('twc.cod_cargo');
        $db->defineSqlSelect('twc.nom_cargo');
        $db->defineSqlFrom('tab_web_proc_sel_curriculo AS psc','sistema');
        $db->defineSqlJoinUsing('tab_web_processo_seletivo AS ps','ps.cod_processo_seletivo = psc.cod_processo_seletivo','INNERJOIN','sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v','v.cod_vaga = ps.cod_vaga','INNERJOIN','sistema');
        $db->defineSqlJoinUsing('tab_web_cargo AS twc', 'twc.cod_cargo = v.cod_cargo', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_curriculo AS c','c.cod_curriculo = psc.cod_curriculo','INNERJOIN','sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf','pf.cod_pessoa_fisica = c.cod_pessoa_fisica','INNERJOIN','sistema');
        $db->defineSqlWhere("psc.cod_proc_sel_curriculo = '{$this->_getParam('id')}'");
        $db->setDebug(false);
        $rsCabecalho = $db->executeSelectQuery();
        //$this->view->assign('rsCabecalho', array('x'=>'y','z'=>'a'));
        $this->view->assign('rsCabecalho', $rsCabecalho[0]);

        $cod_vaga = empty($rsCabecalho[0]['cod_vaga']) ? -1 : $rsCabecalho[0]['cod_vaga'];
        $cod_pessoa_fisica = empty($rsCabecalho[0]['cod_pessoa_fisica']) ? -1 : $rsCabecalho[0]['cod_pessoa_fisica'];
        $nom_pessoa = empty($rsCabecalho[0]['nom_pessoa']) ? '' : $rsCabecalho[0]['nom_pessoa'];
        $tit_vaga = empty($rsCabecalho[0]['tit_vaga']) ? '' : $rsCabecalho[0]['tit_vaga'];
        $this->view->assign('nom_pessoa', $nom_pessoa);
        $this->view->assign('tit_vaga', $tit_vaga);
        $this->view->assign('cod_vaga', $cod_vaga);

        //Dados de Agenda Entrevista
        $db->defineSqlSelect('ae.cod_agenda_entrevista');
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('ae.dta_entrevista');
        $db->defineSqlSelect('ae.cod_vaga');
        $db->defineSqlFrom("tab_web_agenda_entrevista AS ae", 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("ae.ind_status IN ('A','N')");
        $db->defineSqlWhere("ae.cod_pessoa_fisica = '{$cod_pessoa_fisica}'");
        $db->defineSqlWhere("ae.cod_vaga = '{$cod_vaga}'");
        $db->setDebug(false);
        $rsAgenda = $db->executeSelectQuery();

        if (count($rsAgenda) > 0){
            $rsAgenda[0]['dta_entrevista_date'] = Cityware_Format_Mask::fieldMask($rsAgenda[0]['dta_entrevista'], 'DATE', 'd/m/Y');
            $rsAgenda[0]['dta_entrevista_time'] = Cityware_Format_Mask::fieldMask($rsAgenda[0]['dta_entrevista'], 'TIME', 'H:i');
            $dta_entrevista = empty($rsAgenda[0]['dta_entrevista']) ? '' : $rsAgenda[0]['dta_entrevista'];
        }

        // Entrevistas desta vaga
        $db->defineSqlSelect('aa.cod_vaga');
        $db->defineSqlSelect('aa.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('aa.dta_entrevista');
        $db->defineSqlFrom("tab_web_agenda_entrevista AS aa", 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = aa.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("aa.ind_status  = 'A'");
        $db->defineSqlWhere("aa.cod_vaga = '{$cod_vaga}'");
        //$db->defineSqlWhere("aa.cod_agenda_entrevista <> '{$this->_getParam('id')}'");
        $db->defineSqlOrderBy("aa.dta_entrevista ASC");
        $db->setDebug(false);
        $rsEntrevistas = $db->executeSelectQuery();

        $evento_outros = Array();
        foreach ($rsEntrevistas as $key => $value) {
            $evento_outros[date_format(date_create($value['dta_entrevista']), 'n/j/Y')] = Array('nome' => $value['nom_pessoa']);
        }
        $this->setHeadJsScript('window.evento_outros = ' . json_encode($evento_outros), 'text/javascript');

        $evento = Array();
        // Para indicar uma entrevista para o candidato:
        if ( isset($dta_entrevista) && !empty($dta_entrevista) ){
            $evento[date_format(date_create($dta_entrevista), 'n/j/Y')] = Array('nome' => $nom_pessoa);
        }

        $this->setHeadJsScript('window.evento = ' . json_encode($evento), 'text/javascript');

        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_recrutador = $this->_session['idPessoa'];
        $cod_pessoa_juridica = $this->_session['idPessoaJuridica'];
        $nom_usuario = $this->_session['nomeUsuario'];
        $email_usuario = $this->_session['emailRecrutador'];

        // Dados Recrutador
        $db->defineSqlSelect('twp.des_email_principal');
        $db->defineSqlSelect('twp.num_celular');
        $db->defineSqlSelect('twp.des_endereco');
        $db->defineSqlSelect('twp.num_endereco');
        $db->defineSqlSelect('twp.des_complemento');
        $db->defineSqlSelect('twp.num_cep');
        $db->defineSqlSelect('twp.nom_bairro');
        $db->defineSqlFrom("tab_web_pessoa AS twp","sistema");
        $db->defineSqlJoinUsing("tab_web_pessoa_juridica AS twpj", "twpj.cod_pessoa_juridica = twp.cod_pessoa", "INNERJOIN", "sistema");
        $db->defineSqlWhere("twpj.cod_pessoa_juridica = '{$cod_pessoa_juridica}'");
        $db->setDebug(false);
        $rsRecrutador = $db->executeSelectQuery();

        $this->view->nomeRecrutador = $this->_session['nomeUsuario'];

        // Empresa
        $db->defineSqlSelect('twp.des_email_principal');
        $db->defineSqlSelect('twp.num_celular');
        $db->defineSqlSelect('twp.des_endereco');
        $db->defineSqlSelect('twp.num_endereco');
        $db->defineSqlSelect('twp.des_complemento');
        $db->defineSqlSelect('twp.num_cep');
        $db->defineSqlSelect('twp.nom_bairro');
        $db->defineSqlSelect('cdd.cod_cidade');
        $db->defineSqlSelect('cdd.nom_cidade');
        $db->defineSqlSelect('e.cod_estado');
        $db->defineSqlSelect('e.sgl_estado');
        $db->defineSqlFrom("tab_web_pessoa AS twp","sistema");
        $db->defineSqlJoinUsing('tab_web_cidade AS cdd', 'cdd.cod_cidade = twp.cod_cidade', 'LEFTJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_estado AS e', 'e.cod_estado = cdd.cod_estado', 'LEFTJOIN', 'sistema');
        $db->defineSqlWhere("twp.cod_pessoa = '{$cod_pessoa_juridica}'");
        $db->setDebug(false);
        $rsEmpresa = $db->executeSelectQuery();

        $endereco_informacoes = $rsEmpresa[0]['des_endereco']
                .', '.$rsEmpresa[0]['num_endereco']
                .' '.$rsEmpresa[0]['des_complemento']
                .' CEP: '.$rsEmpresa[0]['num_cep']
                .' - '.$rsEmpresa[0]['nom_bairro']
                .' '.$rsEmpresa[0]['nom_cidade']
                .' - '.$rsEmpresa[0]['sgl_estado'];


        $validateRealTime = array();
        // Instancia o adapter do formulário
        $forms = Cityware_Form::factory('zend');
        $forms->setFormIni(array('formName' => 'novo'));

        // Setando uma entrevista nesta data
        $this->view->formVal = array('dta_entrevista_time'=>'08:00',
                                     'ind_status_agenda_entrevista'=>'A',
                                     'nom_contato'=>  $nom_usuario,
                                     'des_email_contato' => $email_usuario,
                                     'num_telefone' => $rsRecrutador[0]['num_celular'],
                                     'des_endereco_informacoes' => $endereco_informacoes
                                    ); //  $rsAgenda[0];

        // Verifica se foi enviado dados pelo formulário
        if ($this->_request->isPost()) {
            $this->view->debug = '';

            // Envia os dados do formulário para o construtor de formulário
            $forms->setPopulateParams($_POST);
            // Monta o formulário com os dados para serem validados
            $varObjectForm = $forms->formBuider($validateRealTime);

            $this->view->varObjectForm = $varObjectForm;

            // Valida dados enviado pelo formulário

            if ($varObjectForm->isValid($_POST)) {
                $cod_agenda_entrevista = 0;
                try {
                    //$dta_entrevista = Cityware_Format_Mask::fieldMask($this->_getParam('dta_entrevista'), 'DATE', 'Y-m-d');
                    $dta_entrevista_date_new = $this->_getParam('dta_entrevista_date') . ' ' . $this->_getParam('dta_entrevista_time');
                    $dt = DateTime::createFromFormat('d/m/Y H:i', $dta_entrevista_date_new);
                    $dta_entrevista_date_new = $dt->format('Y-m-d H:i:s');

                    $db->defineSqlInsert('cod_vaga', $rsCabecalho[0]['cod_vaga']);
                    $db->defineSqlInsert('cod_pessoa_fisica', $rsCabecalho[0]['cod_pessoa_fisica']);
                    $db->defineSqlInsert('cod_pessoa_juridica', $this->_session['idPessoaJuridica']);
                    $db->defineSqlInsert('cod_proc_sel_curriculo', $this->_getParam('id'));
                    $db->defineSqlInsert('nom_contato', $this->_getParam('nom_contato'));
                    $db->defineSqlInsert('num_telefone', $this->_getParam('num_telefone'));
                    $db->defineSqlInsert('des_email_contato', $this->_getParam('des_email_contato'));
                    $db->defineSqlInsert('ind_status_agenda_entrevista', 'A');
                    $db->defineSqlInsert('des_endereco_informacoes', $this->_getParam('des_endereco_informacoes'));
                    $db->defineSqlInsert('des_outros_dados', $this->_getParam('des_outros_dados'));
                    $db->defineSqlInsert('dta_entrevista', $dta_entrevista_date_new);
                    $db->defineSqlInsert('dta_cadastro', date('Y-m-d h:i:s'));
                    $db->defineSqlFrom('tab_web_agenda_entrevista', 'sistema');
                    //$db->defineSqlWhere("cod_agenda_entrevista = '{$this->_getParam('id')}'");
                    $db->setDebug(false);
                    $cod_agenda_entrevista = $db->executeInsertQuery();
                } catch (Exception $exc) {
                    $db->defineCloseConnection();
                    throw new Exception('Erro ao tentar salvar agendamento de entrevista - ' . $exc->getMessage(), 500);
                }
                //$this->_redirect(LINK_DEFAULT . 'recrutador/agendaentrevista/index/msg/sucess');

                if ($cod_agenda_entrevista > 0) {
                    $this->enviarEmail($cod_agenda_entrevista);
                }


                $this->setHeadJsScript("closeFancyboxAndRedirectToUrl();", 'text/javascript');
            } else {
                foreach ($varObjectForm->getErrors() as $key => $value) {
                    if (!empty($value)) {
                        $errosForm[$key] = $value;
                    }
                }

                $arrayMessages = $varObjectForm->getMessages();

                foreach ($arrayMessages as $key => $value) {
                    if (!empty($arrayMessages[$key]['emailAddressInvalidFormat'])) {
                        $arrayMessages[$key]['emailAddressInvalidFormat'] = 'O e-mail informado é inválido.';
                    }
                    if (!empty($arrayMessages[$key]['emailAddressInvalidHostname'])) {
                        $arrayMessages[$key]['emailAddressInvalidHostname'] = 'O e-mail informado é inválido.';
                    }
                    if (!empty($arrayMessages[$key]['hostnameInvalidHostname'])) {
                        $arrayMessages[$key]['hostnameInvalidHostname'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameLocalNameNotAllowed'])) {
                        $arrayMessages[$key]['hostnameLocalNameNotAllowed'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameUndecipherableTld'])) {
                        $arrayMessages[$key]['hostnameUndecipherableTld'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameUnknownTld'])) {
                        $arrayMessages[$key]['hostnameUnknownTld'] = '';
                    }
                    if (!empty($arrayMessages[$key]['hostnameInvalidLocalName'])) {
                        $arrayMessages[$key]['hostnameInvalidLocalName'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressInvalidLocalPart']) or !empty($arrayMessages[$key]['emailAddressDotAtom']) or !empty($arrayMessages[$key]['emailAddressInvalidLocalPart'])) {
                        $arrayMessages[$key]['emailAddressInvalidFormat'] = 'O e-mail informado é inválido.';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressDotAtom'])) {
                        $arrayMessages[$key]['emailAddressDotAtom'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressQuotedString'])) {
                        $arrayMessages[$key]['emailAddressQuotedString'] = '';
                    }

                    if (!empty($arrayMessages[$key]['emailAddressInvalidLocalPart'])) {
                        $arrayMessages[$key]['emailAddressInvalidLocalPart'] = '';
                    }
                    if (!empty($arrayMessages[$key]['missingToken'])) {
                        $arrayMessages[$key]['missingToken'] = 'O e-mail que foi confirmado está diferente do informado.';
                    }
                    if (!empty($arrayMessages[$key]['notSame'])) {
                        $arrayMessages[$key]['notSame'] = 'A senha que foi confirmada está diferente da informada.';
                    }
                }

                // Envia formulário para a camada de visão
                $this->view->formVal = $this->getRequest()->getPost();
                $this->view->form = $varObjectForm->getErrorMessages();
                $this->view->formErros = $errosForm;
                $this->view->formErrosMsg = $arrayMessages;

                ///////$this->setHeadJsScript("fancyAlert('Existem erros no preenchimento das informações sobre o agendamento da entrevista!. <br/>Gentileza conferir e corrigir todos os campos destacados em vermelho', 'err', '');", 'text/javascript');
            }
        } else {

        } // not post
    }

    public function ajaxbuscavagasAction() {
        /* Inicia conexao com o banco de dados */
        $this->noRender();
        if ($this->_getParam('cod_vaga_empresa') != '') {
            $rsVaga = $this->buscaVagas($this->_getParam('cod_vaga_empresa'));
            if (count($rsVaga) > 0) {
                $html = '<option value="">Selecione uma opção ...</option>';
                foreach ($rsVaga as $value) {
                    $html .= '<option value="' . $value['cod_vaga'] . '">' . $value['tit_vaga'].' - '. $value['nom_cargo'] . '</option>';
                }
            } else {
                $html = '<option value="">Nenhum registro encontrado!</option>';
            }
        } else {
            $html = '<option value="">Selecione uma opção ...</option>';
        }
        echo $html;
    }


    private function buscaVagas($cod_vaga_empresa) {
        $db = $this->getConnection();
        $db->defineSqlSelect('twv.cod_vaga');
        $db->defineSqlSelect('twv.tit_vaga');
        $db->defineSqlSelect('twv.tit_vaga');
        $db->defineSqlSelect('twc.nom_cargo');

        $db->defineSqlFrom('tab_web_vaga_empresa_rel AS twver', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS twv', 'twv.cod_vaga = twver.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_cargo AS twc', 'twv.cod_cargo = twc.cod_cargo', 'INNERJOIN', 'sistema');

        $db->defineSqlWhere("twver.cod_vaga_empresa = '{$cod_vaga_empresa}'");
        $db->defineSqlWhere("twv.ind_status IN ('A', 'N')");
        $db->defineSqlOrderBy("twv.tit_vaga ASC'");
        $rsVaga = $db->executeSelectQuery();
        return $rsVaga;
    }

    public function ajaxbuscacandidatosAction() {
        /* Inicia conexao com o banco de dados */
        $this->noRender();
        if ($this->_getParam('cod_vaga_empresa') != '') {
            $cod_vaga_empresa = $this->_getParam('cod_vaga_empresa');
            $cod_vaga = $this->_getParam('cod_vaga') != '' ? $this->_getParam('cod_vaga') : 0;

            $rsCurriculo = $this->buscaCandidatos($cod_vaga_empresa, $cod_vaga);
            if (count($rsCurriculo) > 0) {
                $html = '<option value="">Selecione uma opção ... </option>';
                foreach ($rsCurriculo as $value) {
                    $html .= '<option value="' . $value['cod_pessoa_fisica'] . '">' . $value['nom_pessoa'] . '</option>';
                }
            } else {
                $html = '<option value="">Nenhum registro encontrado! </option>';
            }
        } else {
            $html = '<option value="">Selecione uma opção ...</option>';
        }
        echo $html;
    }

    private function buscaCandidatos($cod_vaga_empresa = 0, $cod_vaga = 0) {
        $db = $this->getConnection();

        //$db->defineSqlSelect('ae.cod_agenda_entrevista');
        //$db->defineSqlSelect('ae.cod_vaga');
        //$db->defineSqlSelect('ae.num_telefone');
        //$db->defineSqlSelect('ae.dta_entrevista');
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        //$db->defineSqlSelect('ae.num_telefone');
        //$db->defineSqlSelect('v.tit_vaga');
        //$db->defineSqlSelect('v.dta_cadastro');
        //$db->defineSqlSelect('ve.cod_vaga_empresa');
        //$db->defineSqlSelect('ve.nom_fantasia_empresa');
        $db->defineSqlFrom('tab_web_agenda_entrevista AS ae', 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa_rel AS ver', 'ver.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa AS ve', 've.cod_vaga_empresa = ver.cod_vaga_empresa', 'INNERJOIN', 'sistema');
        //$db->defineSqlWhere("ae.cod_pessoa_juridica = '{$idPessoaJuridica}'");
        $db->defineSqlWhere("ve.cod_vaga_empresa = '{$cod_vaga_empresa}'");
        if ($cod_vaga > 0) {
            $db->defineSqlWhere("ae.cod_vaga = '{$cod_vaga}'");
        }
        $db->defineSqlOrderBy('pf.nom_pessoa ASC');

        $rs = $db->executeSelectQuery();
        return $rs;
    }

    public function ajaxcancelarentrevistaAction() {
        $id = $this->_getParam('id');
        // Inicia conexao com o banco de dados
        $db = $this->getConnection();
        $this->noRender();
        if ($this->_getParam('id') != '') {
            // Atualiza status da agenda para cancelada
            $db->defineSqlUpdate('ind_status_agenda_entrevista', 'C');
            //$db->defineSqlUpdate('dta_atualizacao', date('Y-m-d h:i:s'));
            $db->defineSqlFrom('tab_web_agenda_entrevista', 'sistema');
            $db->defineSqlWhere("cod_agenda_entrevista = '{$this->_getParam('id')}'");
            $db->setDebug(false);

            if ($db->executeUpdateQuery()) {
                echo json_encode(Array('msg' => 'ok'));
            } else {
                echo json_encode(Array('msg' => 'error'));
            }
        } else {
            echo json_encode(Array('msg' => 'error'));
        }
    }

    private function enviarEmail($cod_agenda_entrevista){
        //$this->view->popup = true;
        //$this->noRender();

        $db = $this->getConnection();
        $db->defineSqlSelect('ae.cod_agenda_entrevista');

        $db->defineSqlSelect('ae.dta_entrevista');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlSelect('p.des_email_principal');
        $db->defineSqlSelect('ve.nom_fantasia_empresa');
        $db->defineSqlSelect('twc.nom_cargo');
        $db->defineSqlSelect('ae.num_telefone');
        $db->defineSqlSelect('ae.nom_contato');
        $db->defineSqlSelect('ae.des_email_contato');
        $db->defineSqlSelect('ae.des_endereco_informacoes');
        $db->defineSqlSelect('ae.des_outros_dados');
        $db->defineSqlSelect('ae.cod_agenda_entrevista');
        $db->defineSqlSelect('twpsc.cod_curriculo');
        $db->defineSqlSelect('ae.cod_vaga');
        $db->defineSqlSelect('ae.num_telefone');
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('ae.ind_status_agenda_entrevista');
        $db->defineSqlSelect('v.tit_vaga');
        $db->defineSqlSelect('v.dta_cadastro');
        $db->defineSqlSelect('ve.cod_vaga_empresa');
        $db->defineSqlFrom('tab_web_agenda_entrevista AS ae', 'sistema');
        $db->defineSqlJoinUsing('tab_web_proc_sel_curriculo AS twpsc', 'twpsc.cod_proc_sel_curriculo = ae.cod_proc_sel_curriculo', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa AS p', 'p.cod_pessoa = pf.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_cargo AS twc', 'twc.cod_cargo = v.cod_cargo', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa_rel AS ver', 'ver.cod_vaga = ae.cod_vaga', 'LEFTJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa AS ve', 've.cod_vaga_empresa = ver.cod_vaga_empresa', 'LEFTJOIN', 'sistema');
        $db->defineSqlWhere("ae.cod_agenda_entrevista = '{$cod_agenda_entrevista}'");
        $db->setDebug(false);
        $rsDados = $db->executeSelectQuery();

        $rsDados[0]['dta_entrevista_date'] = Cityware_Format_Mask::fieldMask($rsDados[0]['dta_entrevista'], 'DATE', 'd/m/Y');
        $rsDados[0]['dta_entrevista_time'] = date_format(date_create($rsDados[0]['dta_entrevista']), 'H:i');

        $this->view->assign('rsDados',$rsDados[0]);
        $email = $rsDados[0]['des_email_principal'];
        $nome = $rsDados[0]['nom_pessoa'];

        $mail = Cityware_Mail::factory('zend');
        $mail->setFrom($this->_session['emailRecrutador'], $this->_session['nomeRecrutador']);
        // Renderiza o template de email
        $bodyHtml = $this->view->render('agendaentrevista/templateEmailAgenda.phtml');
        $mail->setBody($bodyHtml);
        $mail->setSubject('[Ser Humano] - Agendamento de Entrevista');
        $mail->setTo($email, $nome);
        // Envia o email
        $mail->sendMail();
        // Msg de sucesso caso o envio ocorra sem validacoes e erros
    }
}

/*
$mail = Cityware_Mail::factory('zend');
$mail->setFrom($this->_session['emailRecrutador'], $this->_session['nomeRecrutador']);
// Renderiza o template de email
$dados['nomeCandidato'] = $nomesSel['nom_pessoa'][$key];
$this->view->dado = $dados;
$bodyHtml = $this->view->render('busca/templateEmailMsg.phtml');
$mail->setBody($bodyHtml);
$mail->setSubject('[Ser Humano] - ' . $dados['assunto']);
$mail->setTo($value, "Área de Recrutamento - Ser Humano");
// Envia o email
$mail->sendMail();
// Msg de sucesso caso o envio ocorra sem validacoes e erros
$this->setHeadJsScript("fancyAlert('E-mail(s) enviado com sucesso!', 'suc', '');", 'text/javascript');
*/

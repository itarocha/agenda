<?php

/*
 * ** 01 candidatosnaoaproveitados
 * ** 03 candidatossituacao
 * ** 04 candidatoshistoricoprocessos
  05 candidatosranking
 * ** 06 processosacompanhamento
 * ** 07 candidatosadmitidos
  08 candidatosparecer
  09 entrevistasagendadas
 * ** 10 processosposicaodiaria
 * ** 11 candidatosreprovados
 * ** 12 processosrealizados
  13 processosestatisticas
  14 candidatosaprovados

 * ** 07 candidatosadmitidos
  14 candidatosaprovados //: processosacompanhamento
 * ** 04 candidatoshistoricoprocessos
 * ** 01 candidatosnaoaproveitados
  08 candidatosparecer
  05 candidatosranking
 * ** 11 candidatosreprovados
 * ** 03 candidatossituacao
  09 entrevistasagendadas
 * ** 06 processosacompanhamento
  13 processosestatisticas
 * ** 10 processosposicaodiaria
 * ** 12 processosrealizados

 *  */

/**
 * Description of RelatoriosController
 *
 * @author itamar.rocha
 */
class Recrutador_RelatoriosController extends Cityware_Controller_Recrutador {

    const sessaoRelatorios = "SESSAORELATORIOS";
    const REGISTROS_POR_PAGINA = 50;

    private $array_ind_tipo_vaga = array(
        'I' => 'Interna',
        'E' => 'Externa',
        'M' => 'Mista'
            //'V' => 'Nova Vaga',
            //'T' => 'Cargo Temporário'
    );
    private $array_ind_vaga_status = array(
        'AB' => 'Aberta',
        'CA' => 'Cancelada',
        'AV' => 'Em Validação',
        'EX' => 'Expirada',
        'FE' => 'Fechada',
        'NA' => 'Não Validada',
        'VR' => 'Renovada',
        'SU' => 'Suspensa');
    private $array_ind_status_processo = array(
        'A' => 'Aberto',
        'C' => 'Cancelado',
        'F' => 'Fechado',
        'P' => 'Pendente');
    private $array_ind_situacao_candidato = array(
        /*
          'AP' => 'Aprovado',
          'NC' => 'Não compareceu',
          'RE' => 'Reprovado',
          'NI' => 'Não tem interesse',
          'PE' => 'Pendente'
         */
        'AD' => 'Admitido',
        'AP' => 'Aprovado',
        'AC' => 'Assinar contrato',
        'NC' => 'Não compareceu',
        'PE' => 'Pendente',
        'RE' => 'Reprovado',
        'NI' => 'Sem interesse',
        'SB' => 'Stand by',
    );
    private $array_ind_percepcao = array(
        'OT' => 'Ótimo',
        'BO' => 'Bom',
        'RE' => 'Regular',
        'RU' => 'Ruim');
    private $array_ind_status_candidato = array(
        'P' => 'Pendente',
        'A' => 'Aprovado',
        'R' => 'Reprovado');
    private $array_ind_status_fase = array(
        'P' => 'Pendente',
        'C' => 'Concluida',
        'E' => 'Cancelada');
    private $array_ind_status_agenda_entrevista = array(
        'A' => 'Em Aberto',
        'C' => 'Cancelada',
        'N' => 'Não Compareceu',
        'R' => 'Realizada');

    // [nome_relatorio]Action = página de seleção do relatório
    // rel[nome_relatorio]Action = relatório propriamente dito
    // private function getParametros[nome_relatorio] = lê/escreve parâmetros da seção e retorna no formato parâmetros
    // buildQuery[nome_relatorio] = monta ResultSet com o array de parâmetros

    public function _testeAction(){
        echo '<pre>';
        
        $sessao = $this->getLocalSession();
        print_r($sessao);
        
        $engine = new Application_Model_Admin_Assinante_Engine();
        
        $cod_pessoa = 5281;
        $cod_plano_acesso = 1828;
        
        $cod_curriculo = 2941;
        
        //$cod_pessoa = 2803;
        //$cod_vaga = 299;
        //$cod_vaga = 360;
        $cod_vaga = 390;
        
        echo '<br/>$this->getDataProximaAssinatura('.$cod_pessoa.')<br/>';
        $dta = $engine->getDataProximaAssinatura($cod_pessoa);
        echo 'Dta = '.date_format($dta,'d/m/Y').'<br/>';

        //$rs = $engine->incluirPlano(200);
        //print_r($rs);
        $assinatura = $engine->getAssinaturaAtiva($cod_pessoa);
        if ($assinatura){
            print_r($assinatura);
        }
        
        $retorno = $engine->getAssinaturasVencendo('2001-01-01','2014-10-01');
        print_r($retorno);
        
        if ($engine->pessoaPodeAcessarCurriculo($cod_pessoa, $cod_curriculo)){
            echo '$engine->pessoaPodeAcessarCurriculo('.$cod_pessoa.', '.$cod_curriculo.') = TRUE <br/>';
        } else {
            echo '$engine->pessoaPodeAcessarCurriculo('.$cod_pessoa.', '.$cod_curriculo.') = FALSE <br/>';
        }
        
        echo '<br/>$engine->getPacoteComSaldo('.$cod_pessoa.');<br/>';
        $pacote = $engine->getPacoteComSaldo($cod_pessoa);
        if ($pacote) {
            echo 'CodPessoa = '.$pacote->getCodPessoa().'<br/>';
            echo 'NumVisualizacoes = '.$pacote->getNumVisualizacoes().'<br/>';
            echo 'QtdUtilizada = '.$pacote->getQtdUtilizada().'<br/>';
            echo 'QtdRestante = '.$pacote->getQtdRestante().'<br/>';
        }

        echo '<br/>$engine->getSaldoPacote('.$cod_pessoa.', '.$cod_plano_acesso.');<br/>';
        $pacote = $engine->getSaldoPacote($cod_pessoa, $cod_plano_acesso);
        if ($pacote) {
            echo 'CodPessoa = '.$pacote->getCodPessoa().'<br/>';
            echo 'NumVisualizacoes = '.$pacote->getNumVisualizacoes().'<br/>';
            echo 'QtdUtilizada = '.$pacote->getQtdUtilizada().'<br/>';
            echo 'QtdRestante = '.$pacote->getQtdRestante().'<br/>';
        }

        echo '<br/>$engine->getPrimeiroPacoteComCredito('.$cod_pessoa.');<br/>';
        $pacote = $engine->getPrimeiroPacoteComCredito($cod_pessoa);
        if ($pacote) {
            echo '<b>CodPlanoAcesso = '.$pacote->getCodPlanoAcesso().'</b><br/>';
            echo 'CodPessoa = '.$pacote->getCodPessoa().'<br/>';
            echo 'IndTipoPessoa = '.$pacote->getIndTipoPessoa().'<br/>';
            echo 'NumDiasExpiracao = '.$pacote->getNumDiasExpiracao().'<br/>';
            echo 'NumVisualizacoes = '.$pacote->getNumVisualizacoes().'<br/>';
            echo 'QtdUtilizada = '.$pacote->getQtdUtilizada().'<br/>';
            echo 'QtdRestante = '.$pacote->getQtdRestante().'<br/>';
        }
        
        $vaga = $engine->getVagaDeAssinaturaAtiva($cod_vaga); //390
        echo '<br/>$vaga = $engine->getVagaDeAssinaturaAtiva($cod_vaga);<br/>';
        if ($vaga) {
            echo 'CodVaga = '.$vaga->getCodVaga().'<br/>';
            echo 'CodPlanoAcesso = '.$vaga->getCodPlanoAcesso().'<br/>';
            echo 'IndTipoPlano = '.$vaga->getIndTipoPlano().'<br/>';
            echo 'DtaAquisicao = '.$vaga->getDtaAquisicao().'<br/>';
            echo 'DtaExpiracao = '.$vaga->getDtaExpiracao().'<br/>';
        } else {
            echo 'NÃO RETORNOU NADA';
        }
        exit;
        
        //
        //
        //
        //$rs = $engine->pessoaPossuiAssinaturaAtiva($cod_pessoa); //2830
        //$rs = $engine->possuiPacoteComCredito($cod_pessoa);
        //$rs = $engine->getProximoPacoteComCredito($cod_pessoa);
        //$rs = $engine->pessoaPossuiAssinaturaAtiva($cod_pessoa);
        //$rs = $engine->existeVagaAbertaNaoExpirada($cod_pessoa, $cod_vaga);
        echo '<pre>';
        print_r($rs);
        exit;
        
    }

    public function indexAction() {
        
    }

    private function getLocalSession() {
        return Zend_Session::namespaceGet(SESSION_RECRUTADOR);
    }

    private function getSessionNamespace() {
        return new Zend_Session_Namespace(SESSION_RECRUTADOR);
    }

//  Cada página de seleção relatório é uma action. O relatório em si inicia com "rel"
    public function vagasporperiodoAction() {

        $model = new Application_Model_Recrutador_Relatorios_VagasPorPeriodo();

        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_vagasporperiodo.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        $sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendVagaStatus();
        $this->sendTipoVaga();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosVagasPorPeriodo($page);
        $model = new Application_Model_Recrutador_Relatorios_VagasPorPeriodo();
        $rsListagem = $model->buildQueryVagasPorPeriodo($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    public function relvagasporperiodoAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosCandidatosRanking(0);
        $model = new Application_Model_Recrutador_Relatorios_VagasPorPeriodo();
        $rsListagem = $model->buildQueryVagasPorPeriodo($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    private function getParametrosVagasPorPeriodo($page) {
        // Leitura de parâmetros
        //empresa/68/vaga/263/status/A/candidato/2102/datini/01-01-2014/datfim/29-02-2014/        
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['ind_tipo_vaga'] = $this->_getParam('tipo', null);
        $parametros['ind_vaga_status'] = $this->_getParam('status', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }

        return $parametros;
    }

    /////////////////////////  01 - CANDIDATOS NÃO APROVEITADOS /////////////////////
    // 1.1
    public function candidatosnaoaproveitadosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosnaoaproveitados.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosNaoAproveitados($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosNaoAproveitados();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    // 1.2
    public function candidatosnaoaproveitadosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosNaoAproveitados(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosNaoAproveitados();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 1.3
    private function getParametrosCandidatosNaoAproveitados($page) {
        // Leitura de parâmetros
        //empresa/68
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        return $parametros;
    }

    /////////////////////////  03 - SITUAÇÃO DOS CANDIDATOS /////////////////////
    // 3.1
    public function candidatossituacaoAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatossituacao.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendSituacaoCandidato();
        $this->sendVagaStatus();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosSituacao($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosSituacao();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsProcessos = $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }
    }

    // 3.2
    public function candidatossituacaorelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosCandidatosSituacao(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosSituacao();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 3.3
    private function getParametrosCandidatosSituacao($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['ind_vaga_status'] = $this->_getParam('statusvaga', null);
        $parametros['ind_situacao_candidato'] = $this->_getParam('situacaocandidato', null);
        return $parametros;
    }

    /////////////////////////  04 - HISTÓRICO DE PROCESSO SELETIVO /////////////////////
    // 4.1
    public function candidatoshistoricoprocessosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatoshistoricoprocessos.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosHistoricoProcessos($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosHistoricoProcessos();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsProcessos = $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }
    }

    // 4.2
    public function candidatoshistoricoprocessosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosCandidatosHistoricoProcessos(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosHistoricoProcessos();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 4.3
    private function getParametrosCandidatosHistoricoProcessos($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);
        return $parametros;
    }

    /////////////////////////  05 - RANKING DE CANDIDATOS /////////////////////
    // 5.1
    public function candidatosrankingAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosranking.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        $sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusCandidato();
        $this->sendVagaStatus();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosRanking($page);
        $model = new Application_Model_Recrutador_Relatorios_Ranking();
        $rsListagem = $model->buildQueryCandidatosRanking($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsVagas = $this->buscaVagas($cod_vaga_empresa);
        } else {
            $this->view->rsVagas = array();
        }
    }

    // 5.2
    public function candidatosrankingrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosCandidatosRanking(0);
        $model = new Application_Model_Recrutador_Relatorios_Ranking();
        $rsListagem = $model->buildQueryCandidatosRanking($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 5.3
    private function getParametrosCandidatosRanking($page) {
        // Leitura de parâmetros
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_vaga'] = $this->_getParam('vaga', null);

        return $parametros;
    }

    /////////////////////////  06 - ACOMPANHAMENTO DE PROCESSOS /////////////////////
    // 6.1
    public function processosacompanhamentoAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_processosacompanhamento.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();
        $this->sendTipoVaga();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosAcompanhamento($page);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosAcompanhamento();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $rsProcessos = $this->buscaProcessos($cod_vaga_empresa);

            $this->view->rsProcessos = $rsProcessos; // $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }
    }

    // 6.2
    public function processosacompanhamentorelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosAcompanhamento(0);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosAcompanhamento();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 6.3
    private function getParametrosProcessosAcompanhamento($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);
        $parametros['ind_tipo_vaga'] = $this->_getParam('tipovaga', null);

        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }

        return $parametros;
    }

    /////////////////////////  07 - CANDIDATOS ADMITIDOS /////////////////////
    // 7.1
    public function candidatosadmitidosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosadmitidos.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosAdmitidos($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosAdmitidos();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    // 7.2
    public function candidatosadmitidosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosAdmitidos(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosAdmitidos();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 7.3
    private function getParametrosCandidatosAdmitidos($page) {
        // Leitura de parâmetros
        //empresa/68
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }
        return $parametros;
    }

    /////////////////////////  08 - CANDIDATOS - PARECER NAS FASES /////////////////////
    // 8.1
    public function candidatosparecerAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosparecer.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();
        $this->sendStatusCandidato();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosParecer($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosParecer();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsProcessos = $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }

        if (isset($parametros['cod_processo_seletivo']) && !empty($parametros['cod_processo_seletivo'])) {
            $cod_processo_seletivo = $parametros['cod_processo_seletivo']; //$_POST['cod_vaga_empresa'];
            $this->view->rsFases = $this->buscaFasesProcesso($cod_processo_seletivo);
        } else {
            $this->view->rsFases = array();
        }
    }

    // 8.2
    public function candidatosparecerrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosCandidatosParecer(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosParecer();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 8.3
    private function getParametrosCandidatosParecer($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['cod_fase_proc_seletivo'] = $this->_getParam('fase', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);
        $parametros['ind_status_candidato'] = $this->_getParam('statuscandidato', null);

        return $parametros;
    }

    /////////////////////////  09 - ENTREVISTAS AGENDADAS /////////////////////
    // 9.1
    // 009
    public function entrevistasagendadasAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_entrevistasagendadas.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusAgendaEntrevista();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosEntrevistasAgendadas($page);
        $model = new Application_Model_Recrutador_Relatorios_EntrevistasAgendadas();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsVagas = $this->buscaVagas($cod_vaga_empresa);
        } else {
            $this->view->rsVagas = array();
        }

        $this->view->rsCandidatos = array();

        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $this->view->rsProcessos = $this->buscaProcessos($cod_vaga_empresa);

            if (isset($parametros['cod_vaga']) && !empty($parametros['cod_vaga'])) {
                $cod_vaga = $parametros['cod_vaga'];
                $this->view->rsCandidatos = $this->buscaCandidatos($cod_vaga_empresa, $cod_vaga);
            } else {
                $this->view->rsCandidatos = array();
            }
        } else {
            $this->view->rsCandidatos = array();
        }

        if (isset($parametros['cod_processo_seletivo']) && !empty($parametros['cod_processo_seletivo'])) {
            $cod_processo_seletivo = $parametros['cod_processo_seletivo']; //$_POST['cod_vaga_empresa'];
            $this->view->rsFases = $this->buscaFasesProcesso($cod_processo_seletivo);
        } else {
            $this->view->rsFases = array();
        }
    }

    // 9.2
    public function entrevistasagendadasrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $parametros = $this->getParametrosEntrevistasAgendadas(0);
        $model = new Application_Model_Recrutador_Relatorios_EntrevistasAgendadas();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 9.3
    private function getParametrosEntrevistasAgendadas($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_vaga'] = $this->_getParam('vaga', null);
        $parametros['cod_pessoa_fisica'] = $this->_getParam('pessoa', null);
        $parametros['ind_status_agenda_entrevista'] = $this->_getParam('status', null);

        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }

        return $parametros;
    }

    /////////////////////////  10 - ACOMPANHAMENTO DE PROCESSOS /////////////////////
    // 10.1
    public function processosposicaodiariaAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_processosposicaodiaria.js');
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        $this->_session = $this->getLocalSession();
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosPosicaoDiaria($page);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosPosicaoDiaria();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    // 10.2
    public function processosposicaodiariarelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosPosicaoDiaria(0);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosPosicaoDiaria();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 10.3
    private function getParametrosProcessosPosicaoDiaria($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }

        // Parâmetro "dta_final" é sempre fixo com a data atual
        $parametros['dta_final'] = date('d/m/Y');
        /*
          if (isset($parametros['dta_final'])) {
          $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
          } else {
          $parametros['dta_final'] = date('d/m/Y');
          }
         */
        return $parametros;
    }

    /////////////////////////  11 - CANDIDATOS REPROVADOS /////////////////////
    // 11.1
    public function candidatosreprovadosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosreprovados.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosReprovados($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosReprovados();
        $rsListagem = $model->buildQuery($parametros);


        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $rsProcessos = $this->buscaProcessos($cod_vaga_empresa);

            $this->view->rsProcessos = $rsProcessos; // $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    // 11.2
    public function candidatosreprovadosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosReprovados(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosReprovados();
        $rsListagem = $model->buildQuery($parametros);



        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 11.3
    private function getParametrosCandidatosReprovados($page) {
        // Leitura de parâmetros
        //empresa/68
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);

        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);

        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }
        return $parametros;
    }

/////////////////////////  12 - PROCESSOS REALIZADOS PELA EMPRESA /////////////////////
// 12.1
    public function processosrealizadosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_processosrealizados.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();


        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosRealizados($page);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosRealizados();
        $rsListagem = $model->buildQuery($parametros);


        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

// 12.2
    public function processosrealizadosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosRealizados(0);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosRealizados();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 12.3
    private function getParametrosProcessosRealizados($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }
        return $parametros;
    }

    /////////////////////////  13 - PROCESSOS ESTATISTICAS /////////////////////
    // 13.1
    public function processosestatisticasAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/highcharts/highcharts.js');
        $this->setHeadJsLink(URL_DEFAULT . 'js/highcharts/modules/exporting.js');
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_processosestatisticas.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosEstatisticas($page);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosEstatisticas();
        $rsListagem = $model->buildQuery($parametros);
        

        if (isset($rsListagem['db']) and ! empty($rsListagem['db'])) {
            $serie = array();

            $serie[] = Array((string) 'Aberto, à vencer', (float) $rsListagem['db'][0]['qtd_aberta_vencer']);
            $serie[] = Array((string) 'Aberto, já vencido', (float) $rsListagem['db'][0]['qtd_aberta_vencida']);
            $serie[] = Array((string) 'Fechado no prazo', (float) $rsListagem['db'][0]['qtd_fechada_no_prazo']);
            $serie[] = Array((string) 'Fechado após o prazo', (float) $rsListagem['db'][0]['qtd_fechada_em_atraso']);
            $serie[] = Array((string) 'Cancelado', (float) $rsListagem['db'][0]['qtd_cancelada']);

            $chart = Cityware_Chart::factory('highcharts');

            $mobileDetect = new Cityware_Extra_Mobile();
            if ($mobileDetect->isMobile()) {
                //Define padroes para tablet
                if ($mobileDetect->isTablet()) {
                    $chart->setGraphTitle('Relatório estatístico de processos seletivos', 'center', 0, 20, Array('style' => Array('x' => '10%')));

                    $chart->setGlobalOtpions('exporting', Array('enable' => false,
                        'buttons' => Array(
                            'printButton' => Array('enabled' => false),
                            'exportButton' => Array('enabled' => false)
                        ))
                    );
                    $chart->setPlotOptions('pie', array(
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'showInLegend' => false,
                        'dataLabels' => Array('enabled' => true)
                    ));

                    //Define padroes para celular
                } else {
                    $chart->setGraphTitle(null);
                    //$chart->setGraphTitle('Relatório estatístico de processos seletivos','center', 0, 20, Array('style' => Array('x' => '10%')));

                    $chart->setGlobalOtpions('exporting', Array('enable' => false,
                        'buttons' => Array(
                            'printButton' => Array('enabled' => false),
                            'exportButton' => Array('enabled' => false)
                        ))
                    );
                    $chart->setPlotOptions('pie', array(
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'showInLegend' => false,
                        'dataLabels' => Array('enabled' => false)
                    ));
                }
                //Define padroes para desktop
            } else {
                $chart->setGraphTitle('Relatório estatístico de processos seletivos', 'center', 0, 20, Array('style' => Array('x' => '10%')));

                $chart->setGlobalOtpions('exporting', Array('enable' => true,
                    'buttons' => Array(
                        'printButton' => Array('enabled' => true),
                        'exportButton' => Array('enabled' => true)
                    ))
                );
                $chart->setPlotOptions('pie', array(
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'showInLegend' => false,
                    'dataLabels' => Array('enabled' => true)
                ));
            }



            $chart->toggleLegend(true);


            $chart->setGraphType('pie');

            //$chart->setTooltip('pointFormat', '{series.name}: {point.percentage}%');
            //$chart->setTooltip('percentageDecimals', 2);

            $chart->setSerie('Total', $serie);


            //$chart->debug();
            $renderChart = $chart->renderChart('chart1');
            $this->view->chart = $renderChart;
            $this->setHeadJsScript($renderChart['script'], 'text/javascript');
        }

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);
    }

    // 13.2
    public function processosestatisticasrelAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/highcharts/highcharts.js');
        $this->setHeadJsLink(URL_DEFAULT . 'js/highcharts/modules/exporting.js');
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosProcessosEstatisticas(0);
        $model = new Application_Model_Recrutador_Relatorios_ProcessosEstatisticas();
        $rsListagem = $model->buildQuery($parametros);
        
        if (isset($rsListagem['db']) and ! empty($rsListagem['db'])) {
            $serie = array();

            $serie[] = Array((string) 'Aberto, à vencer', (float) $rsListagem['db'][0]['qtd_aberta_vencer']);
            $serie[] = Array((string) 'Aberto, já vencido', (float) $rsListagem['db'][0]['qtd_aberta_vencida']);
            $serie[] = Array((string) 'Fechado no prazo', (float) $rsListagem['db'][0]['qtd_fechada_no_prazo']);
            $serie[] = Array((string) 'Fechado após o prazo', (float) $rsListagem['db'][0]['qtd_fechada_em_atraso']);
            $serie[] = Array((string) 'Cancelado', (float) $rsListagem['db'][0]['qtd_cancelada']);

            $chart = Cityware_Chart::factory('highcharts');

            $mobileDetect = new Cityware_Extra_Mobile();
            if ($mobileDetect->isMobile()) {
                //Define padroes para tablet
                if ($mobileDetect->isTablet()) {
                    $chart->setGraphTitle('Relatório estatístico de processos seletivos', 'center', 0, 20, Array('style' => Array('x' => '10%')));

                    $chart->setGlobalOtpions('exporting', Array('enable' => false,
                        'buttons' => Array(
                            'printButton' => Array('enabled' => false),
                            'exportButton' => Array('enabled' => false)
                        ))
                    );
                    $chart->setPlotOptions('pie', array(
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'showInLegend' => false,
                        'dataLabels' => Array('enabled' => true)
                    ));

                    //Define padroes para celular
                } else {
                    $chart->setGraphTitle(null);
                    //$chart->setGraphTitle('Relatório estatístico de processos seletivos','center', 0, 20, Array('style' => Array('x' => '10%')));

                    $chart->setGlobalOtpions('exporting', Array('enable' => false,
                        'buttons' => Array(
                            'printButton' => Array('enabled' => false),
                            'exportButton' => Array('enabled' => false)
                        ))
                    );
                    $chart->setPlotOptions('pie', array(
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'showInLegend' => false,
                        'dataLabels' => Array('enabled' => false)
                    ));
                }
                //Define padroes para desktop
            } else {
                $chart->setGraphTitle('Relatório estatístico de processos seletivos', 'center', 0, 20, Array('style' => Array('x' => '10%')));

                $chart->setGlobalOtpions('exporting', Array('enable' => true,
                    'buttons' => Array(
                        'printButton' => Array('enabled' => true),
                        'exportButton' => Array('enabled' => true)
                    ))
                );
                $chart->setPlotOptions('pie', array(
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'showInLegend' => false,
                    'dataLabels' => Array('enabled' => true)
                ));
            }



            $chart->toggleLegend(true);


            $chart->setGraphType('pie');

            //$chart->setTooltip('pointFormat', '{series.name}: {point.percentage}%');
            //$chart->setTooltip('percentageDecimals', 2);

            $chart->setSerie('Total', $serie);


            //$chart->debug();
            $renderChart = $chart->renderChart('chart1');
            $this->view->chart = $renderChart;
            $this->setHeadJsScript($renderChart['script'], 'text/javascript');
        }

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 13.3
    private function getParametrosProcessosEstatisticas($page) {
        // Leitura de parâmetros
        //empresa/68
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }
        return $parametros;
    }

    /////////////////////////  14 - CANDIDATOS APROVADOS /////////////////////
    // 14.1
    public function candidatosaprovadosAction() {
        $this->setHeadJsLink(URL_DEFAULT . 'js/recrutador/relatorios_candidatosaprovados.js');

        $this->_session = $this->getLocalSession();
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];
        $sessionNamespace = $this->getSessionNamespace();
        $sessionNamespace->setExpirationSeconds(3600);
        //$sessaoRelatorios = self::sessaoRelatorios;
        $page = $this->_getParam('page', null);
        if (empty($page)) {
            $page = 1;
        }

        // Envio de resultsets para Selects
        $this->sendEmpresa();
        $this->sendStatusProcesso();
        //$this->sendTipoVaga();
        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosAprovados($page);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosAprovados();
        $rsListagem = $model->buildQuery($parametros);

        // Envia Resultados
        $this->view->assign('rsListagem', $rsListagem['db']);
        $this->view->assign('pagination', $rsListagem['page']);
        $this->view->assign('paginationTotalCount', $rsListagem['page']->getTotalItemCount());
        $this->view->assign('formVal', $parametros/* $sessionNamespace->relatorios[$sessaoRelatorios] */);

        // Itamar
        if (isset($parametros['cod_vaga_empresa']) && !empty($parametros['cod_vaga_empresa'])) {
            $cod_vaga_empresa = $parametros['cod_vaga_empresa']; //$_POST['cod_vaga_empresa'];
            $rsProcessos = $this->buscaProcessos($cod_vaga_empresa);

            $this->view->rsProcessos = $rsProcessos; // $this->buscaProcessos($cod_vaga_empresa);
        } else {
            $this->view->rsProcessos = array();
        }
    }

    // 14.2
    public function candidatosaprovadosrelAction() {
        $this->view->popup = true;
        $this->view->nomeRecrutador = $this->_session['nomeRecrutador'];

        // Get Parâmetros do Relatório
        $parametros = $this->getParametrosCandidatosAprovados(0);
        $model = new Application_Model_Recrutador_Relatorios_CandidatosAprovados();
        $rsListagem = $model->buildQuery($parametros);

        $this->view->assign('rsListagem', $rsListagem['db']);
    }

    // Isso busca os parâmetros da sessão para montagem do relatório
    // 14.3
    private function getParametrosCandidatosAprovados($page) {
        // Leitura de parâmetros
        //empresa/124/processo/191/statusvaga/AB/statuscandidato/P
        $parametros = array();
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $cod_pessoa_juridica = isset($this->_session['idPessoaJuridica']) && !empty($this->_session['idPessoaJuridica']) ? $this->_session['idPessoaJuridica'] : 0;
        $tipo_usuario = isset($this->_session['tipoUsuario']) && !empty($this->_session['tipoUsuario']) ? $this->_session['tipoUsuario'] : "";
        $cod_recrutador = isset($this->_session['idRecrutador']) && !empty($this->_session['idRecrutador']) ? $this->_session['idRecrutador'] : 0;
        $parametros['cod_pessoa_juridica'] = $cod_pessoa_juridica;
        $parametros['tipo_usuario'] = $tipo_usuario;
        $parametros['cod_recrutador'] = $cod_recrutador;
        $parametros['page'] = $page;
        $parametros['perpage'] = self::REGISTROS_POR_PAGINA;

        $parametros['cod_vaga_empresa'] = $this->_getParam('empresa', null);
        $parametros['cod_processo_seletivo'] = $this->_getParam('processo', null);
        $parametros['ind_status_processo'] = $this->_getParam('statusprocesso', null);

        $parametros['dta_inicial'] = $this->_getParam('datini', null);
        $parametros['dta_final'] = $this->_getParam('datfim', null);
        if (isset($parametros['dta_inicial'])) {
            $parametros['dta_inicial'] = str_replace('-', '/', $parametros['dta_inicial']);
        }
        if (isset($parametros['dta_final'])) {
            $parametros['dta_final'] = str_replace('-', '/', $parametros['dta_final']);
        }

        return $parametros;
    }

    /*     * *********************************************************
     * CHAMADAS AJAX
     * ********************************************************* */

    public function ajaxbuscaprocessosAction() {
        /* Inicia conexao com o banco de dados */
        $this->noRender();
        if ($this->_getParam('cod_vaga_empresa') != '') {
            $rs = $this->buscaProcessos($this->_getParam('cod_vaga_empresa'));
            if (count($rs) > 0) {
                $html = '<option value="">Selecione uma opção ...</option>';
                foreach ($rs as $value) {
                    $html .= '<option value="' . $value['cod_processo_seletivo'] . '">' . $value['nom_processo_seletivo'] . '</option>';
                }
            } else {
                $html = '<option value="">Nenhum registro encontrado!</option>';
            }
        } else {
            $html = '<option value="">Selecione uma opção ...</option>';
        }
        echo $html;
    }

    public function ajaxbuscavagasAction() {
        /* Inicia conexao com o banco de dados */
        $this->noRender();
        if ($this->_getParam('cod_vaga_empresa') != '') {
            $rs = $this->buscaVagas($this->_getParam('cod_vaga_empresa'));
            if (count($rs) > 0) {
                $html = '<option value="">Selecione uma opção ...</option>';
                foreach ($rs as $value) {
                    $html .= '<option value="' . $value['cod_vaga'] . '">' . $value['tit_vaga'] . ' - ' . $value['nom_cargo'] . '</option>';
                }
            } else {
                $html = '<option value="">Nenhum registro encontrado!</option>';
            }
        } else {
            $html = '<option value="">Selecione uma opção ...</option>';
        }
        echo $html;
    }

    public function ajaxbuscafasesAction() {
        /* Inicia conexao com o banco de dados */
//echo '<pre>';
//print_r($this->_getAllParams());
//exit;
        $this->noRender();
        if ($this->_getParam('cod_processo_seletivo') != '') {
            $rs = $this->buscaFasesProcesso($this->_getParam('cod_processo_seletivo'));
            if (count($rs) > 0) {
                $html = '<option value="">Selecione uma opção ...</option>';
                foreach ($rs as $value) {
                    $html .= '<option value="' . $value['cod_fase_proc_seletivo'] . '">' . $value['nom_fase_proc_seletivo'] . '</option>';
                }
            } else {
                $html = '<option value="">Nenhum registro encontrado!</option>';
            }
        } else {
            $html = '<option value="">Selecione uma opção ...</option>';
        }
        echo $html;
    }

    /*     * **************************************
     * FUNÇÕES PRIVADAS
     * ************************************** */

    private function buscaProcessos($cod_vaga_empresa) {
        $cod_vaga_empresa = isset($cod_vaga_empresa) && !empty($cod_vaga_empresa) ? $cod_vaga_empresa : 0;

        $db = $this->getConnection();
//$db->defineSqlSelect('twv.cod_vaga');
//$db->defineSqlSelect('twv.tit_vaga');
//$db->defineSqlSelect('twv.tit_vaga');
        $db->defineSqlSelect('twps.cod_processo_seletivo');
        $db->defineSqlSelect('twps.nom_processo_seletivo');

        $db->defineSqlFrom('tab_web_vaga_empresa_rel AS twver', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS twv', 'twv.cod_vaga = twver.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_processo_seletivo AS twps', 'twv.cod_vaga = twps.cod_vaga', 'INNERJOIN', 'sistema');

        $db->defineSqlWhere("twver.cod_vaga_empresa = '{$cod_vaga_empresa}'");
        $db->defineSqlWhere("twv.ind_status IN ('A', 'N')");
        $db->defineSqlOrderBy("twps.nom_processo_seletivo ASC'");
        $db->setDebug(false);
        $rs = $db->executeSelectQuery();
        return $rs;
    }

    private function buscaFasesProcesso($cod_processo_seletivo) {
        // Fases do Processo Seletivo
        $db = $this->getConnection();

        $db->defineSqlSelect('psf.cod_fase_proc_seletivo');
        $db->defineSqlSelect('f.nom_fase_proc_seletivo');
        $db->defineSqlSelect('f.num_ordem');
        $db->defineSqlFrom("tab_web_proc_sel_fase AS psf", 'sistema');
        $db->defineSqlJoinUsing('tab_web_fase_proc_seletivo AS f', 'f.cod_fase_proc_seletivo = psf.cod_fase_proc_seletivo', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("psf.cod_processo_seletivo = '{$cod_processo_seletivo}'");
        $db->setDebug(false);
        $rs = $db->executeSelectQuery();
        return $rs;
    }

    private function buscaVagas($cod_vaga_empresa) {
        $cod_vaga_empresa = isset($cod_vaga_empresa) && !empty($cod_vaga_empresa) ? $cod_vaga_empresa : 0;

        $db = $this->getConnection();
        $db->defineSqlSelect('twv.cod_vaga');
        $db->defineSqlSelect('twv.tit_vaga');
        $db->defineSqlSelect('twv.ind_vaga_status');
        $db->defineSqlSelect('twv.dta_abertura_vaga');
        $db->defineSqlSelect('twca.nom_cargo');
        $db->defineSqlFrom('tab_web_vaga_empresa_rel AS twver', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS twv', 'twv.cod_vaga = twver.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_cargo AS twca', 'twca.cod_cargo = twv.cod_cargo', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("twver.cod_vaga_empresa = '{$cod_vaga_empresa}'");
        $db->defineSqlWhere("twv.ind_status IN ('A', 'N')");
        $db->defineSqlOrderBy("twv.cod_vaga DESC");
        $db->setDebug(false);
        $rs = $db->executeSelectQuery();
        return $rs;
    }

    public function ajaxbuscacandidatosAction() {
        /* Inicia conexao com o banco de dados */
        $this->noRender();
        if ($this->_getParam('cod_vaga_empresa') != '') {
            $cod_vaga_empresa = $this->_getParam('cod_vaga_empresa');
            $cod_vaga = $this->_getParam('cod_vaga') != '' ? $this->_getParam('cod_vaga') : 0;

            $rsCurriculo = $this->buscaCandidatos($cod_vaga_empresa, $cod_vaga);

            /*
              echo '<pre>';
              print_r($rsCurriculo);
              exit;
             */

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
        $db->defineSqlDistinct();
        $db->defineSqlSelect('ae.cod_pessoa_fisica');
        $db->defineSqlSelect('pf.nom_pessoa');
        $db->defineSqlFrom('tab_web_agenda_entrevista AS ae', 'sistema');
        $db->defineSqlJoinUsing('tab_web_pessoa_fisica AS pf', 'pf.cod_pessoa_fisica = ae.cod_pessoa_fisica', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS v', 'v.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa_rel AS ver', 'ver.cod_vaga = ae.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa AS ve', 've.cod_vaga_empresa = ver.cod_vaga_empresa', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("ve.cod_vaga_empresa = '{$cod_vaga_empresa}'");

        //$db->setDebug(true);

        if ($cod_vaga > 0) {
            $db->defineSqlWhere("ae.cod_vaga = '{$cod_vaga}'");
        }
        $db->defineSqlOrderBy('pf.nom_pessoa ASC');

        $rs = $db->executeSelectQuery();
        return $rs;
    }

    /*     * *********************************************************
     * ENVIO DE RESULTSETS PARA A VIEW
     * ********************************************************** */

    private function sendEmpresa() {
        $this->_session = Zend_Session::namespaceGet(SESSION_RECRUTADOR);
        $db = $this->getConnection();
        $db->defineSqlDistinct();
        $db->defineSqlSelect('twve.cod_vaga_empresa');
        $db->defineSqlSelect('twve.nom_fantasia_empresa');
//$db->defineSqlSelect('twc.nom_cargo');
        $db->defineSqlFrom('tab_web_vaga_empresa AS twve', 'sistema');
        $db->defineSqlJoinUsing('tab_web_recrutador_empresa_rel AS twrer', 'twrer.cod_vaga_empresa = twve.cod_vaga_empresa', 'LEFTJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_recrutador_empresa AS twre', 'twre.cod_pessoa = twrer.cod_pessoa_recrutador', 'LEFTJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga_empresa_rel AS twver', 'twver.cod_vaga_empresa = twve.cod_vaga_empresa', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_vaga AS twv', 'twv.cod_vaga = twver.cod_vaga', 'INNERJOIN', 'sistema');
        $db->defineSqlJoinUsing('tab_web_cargo AS twc', 'twc.cod_cargo = twv.cod_cargo', 'INNERJOIN', 'sistema');
        $db->defineSqlWhere("twve.cod_pessoa_juridica = '{$this->_session['idPessoaJuridica']}'");
        $db->defineSqlWhere("twve.ind_status IN ('A','N')");
        if (isset($this->_session['tipoUsuario']) and $this->_session['tipoUsuario'] == 'CRI') {
            $db->defineSqlWhere("twre.cod_pessoa = '{$this->_session['idRecrutador']}'");
        }
        $db->defineSqlOrderBy("twve.nom_fantasia_empresa ASC");
        $db->setDebug(false);
        $rsEmpresa = $db->executeSelectQuery();
        $this->view->assign('rsEmpresa', $rsEmpresa);
    }

    private function sendTipoVaga() {
        $this->view->assign('rsTipoVaga', $this->array_ind_tipo_vaga);
    }

    private function sendVagaStatus() {
        $this->view->assign('rsVagaStatus', $this->array_ind_vaga_status);
    }

    private function sendStatusCandidato() {
        $this->view->assign('rsStatusCandidato', $this->array_ind_status_candidato);
    }

    private function sendSituacaoCandidato() {
        $this->view->assign('rsSituacaoCandidato', $this->array_ind_situacao_candidato);
    }

    private function sendStatusProcesso() {
        $this->view->assign('rsStatusProcesso', $this->array_ind_status_processo);
    }

    private function sendStatusAgendaEntrevista() {
        $this->view->assign('rsStatusAgendaEntrevista', $this->array_ind_status_agenda_entrevista);
    }

}

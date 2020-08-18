<?php

error_reporting(E_ERROR);
ini_set('display_errors', 0);
include('phpQuery-onefile.php');


function __autoload($classe) {
    $pastas = array('model', 'controller');
    foreach ($pastas as $pasta) {
        if (file_exists("../class/$pasta/$classe.class.php")) {
            include_once "../class/$pasta/$classe.class.php";
        } 
        
    }
}


function simple_curl($url, $post = array(), $get = array()) {
    $url = explode('?', $url, 2);
    if (count($url) === 2) {
        $temp_get = array();
        parse_str($url[1], $temp_get);
        $get = array_merge($get, $temp_get);
    }

    $ch = curl_init($url[0] . "?" . http_build_query($get));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($ch);
}

$cep = $_GET['cep'];

$html = simple_curl('http://m.correios.com.br/movel/buscaCepConfirma.do', array(
    'cepEntrada' => $cep,
    'tipoCep' => '',
    'cepTemp' => '',
    'metodo' => 'buscarCep'
        ));

phpQuery::newDocumentHTML($html, $charset = 'utf-8');

global $dados;
$dados = array(
            'logradouro' => trim(pq('.caixacampobranco .resposta:contains("Logradouro: ") + .respostadestaque:eq(0)')->html()),
            'bairro' => trim(pq('.caixacampobranco .resposta:contains("Bairro: ") + .respostadestaque:eq(0)')->html()),
            'cidade/uf' => trim(pq('.caixacampobranco .resposta:contains("Localidade / UF: ") + .respostadestaque:eq(0)')->html()),
            'cep' => trim(pq('.caixacampobranco .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html())
);

$dados['cidade/uf'] = explode('/', $dados['cidade/uf']);
$dados['cidade'] = trim($dados['cidade/uf'][0]);
$dados['uf'] = trim($dados['cidade/uf'][1]);
unset($dados['cidade/uf']);


Transacao::open();
    $estado = Busca::model("SELECT * FROM ESTADO WHERE sigla LIKE ?",array($dados['uf']));
    $cidade = Busca::model("SELECT * FROM CIDADE where nome like ? and idestado=?", array($dados['cidade'], $estado[0]->idestado));

    $dados["estado_id"] = $estado[0]->idestado;
    $dados["cidade_id"] = $cidade[0]->idcidade;

    $tmp = new GetSelectEstadoCidades($estado[0]->idestado);
    $dados["msg"] = $tmp->controller();

Transacao::close();


die(json_encode($dados));

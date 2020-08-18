<?php

class Template {

    private $arquivo;
    private $valores = array();
    public static $startKey = "[@";
    public static $endKey = "]";

    public function __construct($arquivo, $chave = null) {
        if ($arquivo instanceof Template) {
            $this->arquivo = $arquivo->getContent();
            $this->valores = $arquivo->getKeys();
        } else {
            if (file_exists($arquivo)) {
                $this->arquivo = file_get_contents($arquivo);
            } else {
                $this->arquivo = $arquivo;
            }
            if ($chave != null) {
                $this->valores = $chave;
            }
        }
    }

    public function getAllKeysInTemplate() {
        $splits = explode(self::$startKey, $this->arquivo);
        $result = array();
        foreach ($splits as $key => $value) {
            if ($key == 0)
                continue;
            $result[] = explode(self::$endKey, $value)[0];
        }
        return array_unique($result);
    }

    public function set($chave, $valor) {
        $this->valores[$chave] = $valor;
    }

    public function output() {
        foreach ($this->valores as $chave => $valor) {
            $tagToValor = self::$startKey . $chave . self::$endKey;
            $this->arquivo = str_replace($tagToValor, $valor, $this->arquivo);
        }
        return $this->arquivo;
    }

    public function getKeys() {
        return $this->valores;
    }

    public function getContent() {
        return $this->arquivo;
    }

    static public function join($templates, $separador = "\n") {

        $saida = "";

        foreach ($templates as $template) {
            if (get_class($template) !== "Template") {
                $conteudo = "Erro, tipo incorreto - Template esperado.";
            } else {
                $conteudo = $template->output();
            }
            $saida .= $conteudo . $separador;
        }
        return $saida;
    }

    public static function encodeStrWithKey($str) {
        return self::$startKey . $str . self::$endKey;
    }

}

?>
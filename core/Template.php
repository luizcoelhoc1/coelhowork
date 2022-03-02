<?php

class Template {

    private $file;
    private $values = array();
    public static $startKey = "[@";
    public static $endKey = "]";

    public function __construct($resource, $values = []) {
        if ($resource instanceof Template) {
            $this->constructorByAnotherTemplate($resource);
        } else if (file_exists($resource)) {
            $this->constructorByFile($resource, $values);
        } else if (gettype($resource) == "string") {
            $this->constructorByString($resource, $values);
        }
    }

    public function constructorByAnotherTemplate(Template $anotherTemplate) {
        $this->file = $anotherTemplate->getContent();
        $this->values = $anotherTemplate->getKeys();
    }

    public function constructorByFile(string $file, $values = []) {
        $this->file = file_get_contents($file);
        $this->values = $values;
    }

    public function constructorByString(string $string, $values = []) {
        $this->file = $string;
        $this->values = $values;
    }

    public function getAllKeysInTemplate() {
        $splits = explode(self::$startKey, $this->file);
        $result = array();
        foreach ($splits as $key => $value) {
            if ($key == 0)
                continue;
            $result[] = explode(self::$endKey, $value)[0];
        }
        return array_unique($result);
    }

    public function set($key, $valor) {
        $this->values[$key] = $valor;
    }

    public function output() {
        foreach ($this->values as $key => $valor) {
            $tagToValor = self::$startKey . $key . self::$endKey;
            $this->file = str_replace($tagToValor, $valor, $this->file);
        }
        return $this->file;
    }

    public function getKeys() {
        return $this->values;
    }

    public function getContent() {
        return $this->file;
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
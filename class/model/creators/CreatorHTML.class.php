<?php

/*
 * 
 * Classe usada para criar algumas tags dinamicamente
 */

final class CreatorHTML {

    private function __construct() {
        
    }

    /**
     * Cria uma tag <select> dinamicamente <br />
     * @param options é um array que contem as opções formarão o select, onde a a chave desse array é o valor do option e o valor é o que será mostrado
     * @param $atributeOption em options é possível passar um array de stdClass, Deve ser informado aqui, então, qual é o atributo que será utilizado na view
     * @param templateSelect <ul><li><b>Nulo:</b> O layout do select será padrão e o <b>$attributes</b> deve ser especificado</li><li><b>Definido:</b> Este parâmetro poderá conter um objeto Template, uma string ou um caminho de arquivo que será utilizado como layout do select.<br /> O conteudo final deste parâmetro deve conter a sequência de caracteres <b>[@options]</b> que será usado para definir as opções do select</li></ul>
     * @param attributes Será os atributos extras do select caso não seja especificado um template. Exemplo: name="nome_do_select" onchange="scriptx(this);"
     * @param templateOption Similar ao templateSelect <br /><ul><li><b>Nulo:</b> O layout das options será padrão</li><li><b>Definido:</b> Este parâmetro poderá conter um objeto Template, uma string ou um caminho de arquivo que será utilizado como layout das options.<br /> O conteudo final deste parâmetro deve conter as seguintes sequências de caracteres: <ul><li><b>[@value]</b> será usado para definir o valor da tag <option> </li> <li><b>[@view]</b> será usado para definir o conteudo da tag <option></li> </ul> </li></ul>
     * @param selectedValue Caso seja necessário ter um valor que venha prédefinido defina este parâmetro com o valor desejado
     * @return string Contem o select construido caso não contenha erros
     * @return boolean Falso caso contenha erros
     */
    static public function select(array $options, $selectedValue = null, $templateSelect = null, $templateOption = null) {

        $templateSelect = ($templateSelect === null) ? new Template('<select>[@options]</select>') : new Template($templateSelect);
        $templateOption = ($templateOption === null) ? new Template('<option value="[@value]" [@selected]>[@view]</option>') : new Template($templateOption);


        $optionFinal = "";
        foreach ($options as $value => $view) {
            $option = clone $templateOption;
            $option->set("value", $value);
            $option->set("view", $view);
            if ($selectedValue == $value and $selectedValue !== null) {
                $option->set("selected", "selected");
            } else {
                $option->set("selected", "");
            }
            $optionFinal .= $option->output();
        }
        $templateSelect->set("options", $optionFinal);

        return gettype($templateSelect->output()) == "string" ? $templateSelect->getContent() : FALSE;
    }

    static public function table($datas) {
        
    }

}

<?php

class Home extends Controller {

    public function get(...$params) {
        $this->template = "template/wellcomeTemplate.php";
        loadView("home/wellcome", ["teste" => "sadiojajdios"]);
    }

}
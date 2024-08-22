<?php

class Home extends ServerRenderController {

    public function __construct() {
        parent::__construct(
            openTransaction: false
        );
    }

    public function get(...$params) {
        $this->template = "template/wellcomeTemplate.php";
        loadView("home/wellcome", ["teste" => "sadiojajdios"]);
    }

}
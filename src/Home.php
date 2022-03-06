<?php

class Home extends FullRequestController {

    public function get(...$params) {
        $this->template = "template/wellcomeTemplate.php";
        loadView("home/wellcome", ["teste" => "sadiojajdios"]);
    }

}
<?php

namespace App\controller\api;

use \Coelho\{View, controllers\RestController};

class TestApiController extends RestController {

    public function __construct() {
        parent::__construct();
    }

    public function GET($x = 5) {
        return ["x" => $x];
    }

    public function POST(...$params) {
        return "ba";
    }

    public function DELETE($id) {
        return View::load("home/wellcome", ["foo" => "bar"]);
    }

}
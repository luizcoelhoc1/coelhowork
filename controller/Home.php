<?php

namespace App\controller;

use App\model\TestModel;
use Coelho\{
    View,
    controllers\ServerRenderController
};
use Coelho\database\Transaction;

class Home extends ServerRenderController {

    public function __construct() {
        parent::__construct();
        $this->template = "template/wellcomeTemplate.php";
        $this->TestModel = new TestModel(Transaction::get());
    }

    public function GET() {
        throw new \Exception("sla", 400);
        return View::load("home/wellcome", ["foo" => "bar"]);
    }

    public function POST(...$params) {
        return View::load("home/wellcome", ["foo" => "bar"]);
    }

}
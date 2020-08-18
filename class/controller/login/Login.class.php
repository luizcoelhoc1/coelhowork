<?php

/**
 * Login page is managed by this class
 *
 * @author luizcoelhoc1
 */
class Login {

    function __construct() {
        $this->required = [
            "session" => true
        ];
        $this->layout = "view/login/main.tpl";
    }

    function index() {
        $fb = new Facebook\Facebook([
            'app_id' => '', // Replace {app-id} with your app id
            'app_secret' => '',
            'default_graph_version' => 'v2.2',
        ]);
		
		
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl($url = "http://$_SERVER[SERVER_NAME]"//"http://luizcoelhoc1.hostei.com"
                . str_replace(getcwd(), "", __ROOT) . "/"  // /framework/version/1.0
                . "plugins/FacebookLogin/callback.php?urlReturn="
                . urlencode("http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]")
        );

        $this->data->urlFacebook = htmlspecialchars($loginUrl);

        if ($this->incorrectPass) {
            $this->data->user = $_POST["user"];
            $this->data->msgErro = "Senha ou login incorretos";
        } else {
            $this->data->msgErro = "";
        }
        header_remove();
    }

}

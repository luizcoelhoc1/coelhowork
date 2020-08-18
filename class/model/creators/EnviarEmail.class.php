<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EnviarRecibo
 *
 * @author Administrador
 */
class EnviarEmail {

    private $pessoa;
    private $mensagem;
    private $email;

    public function __construct() {
        $this->pessoa = $pessoaReceberRecibo;
        $this->mensagem = $mensagem;
        $this->email = $email;
    }

    public static function model($pessoa, $email, $assunto, $mensagem) {
        try {

            //define conf email
            $phpmailer = new PHPMailer;
            $phpmailer->Charset = "UTF-8";
            $phpmailer->isSMTP();
            $phpmailer->SMTPDebug = 0;
            $phpmailer->Debugoutput = "html";


            $mail->SMTPAuth = true; // 'true' para autenticação
            $mail->Mailer = "smtp"; //Usando protocolo SMTP
            $mail->Host = "smtp.mail.yahoo.com"; //seu servidor SMTP
            $mail->Username = "luizcoelhoc1";
            $mail->Password = "94671835liz"; // senha de SMTP
            
            /**/
            $mail->From = "luizcoelhoc1@yahoo.com";
            $mail->FromName = "Teste";


            $phpmailer->setFrom("tendadeanuncios@gmail.com", "Tenda de Anúncios");
            $phpmailer->Username = "tendadeanuncios@gmail.com";
            $phpmailer->password = "Tenda!123";

            //define o e-mail
            $phpmailer->IsHTML(true);
            $phpmailer->addAddress($email, $pessoa); //pessoa e seu e-mail
            $phpmailer->Subject = $assunto; //assunto do e-mail
            $phpmailer->Body = utf8_decode($mensagem); //conteudo da mensagem
            $phpmailer->AltBody = "";

            if (!$phpmailer->send()) {
                $retorno["erro"] = true;
                $retorno["msg"] = "Erro no envio da mensagem" . $phpmailer->ErrorInfo;
            } else {
                $retorno["erro"] = false;
                $retorno["msg"] = "Mensagem enviada com sucesso!";
            }
        } catch (Exception $e) {
            $retorno["erro"] = true;
            $retorno["msg"] = "Ocorreu um erro, entre em contato com o Administrador " . $e->getMessage();
        }
        return $retorno;
    }

}

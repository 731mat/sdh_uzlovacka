<?php

namespace App\Model\EmailManager;

use App\Model\ClientConnector;
use Exception;
use Nette;
use Latte;
use App\Model\ISPadminManager;
use Nette\Application\Responses\JsonResponse;
use Nette\Mail\Message;


class SendEmailManager
{

    use Nette\SmartObject;

    public static function sendEmail($email,$predmet,$name,$text,$hash){

        $mail = new Message;
        $latte = new Latte\Engine;
        $from_email = 'Uzlovacka hasici <hlousek@bnet-internet.cz>';

        $emailParams = [
            'name'=>$name,
            'hash' => $hash,
            'text' => $text,
        ];

        $mail->setFrom($from_email)
            ->addTo($email)
            ->addTo('matej.hlousek@email.cz')
            ->setSubject($predmet)
            ->setHtmlBody($latte->renderToString(__DIR__ .'/theme.latte',$emailParams));

        $mailer = new Nette\Mail\SmtpMailer(['host' => 'smtp.bnet-internet.cz', 'username' => '', 'password' => '']);
        $mailer->send($mail);
    }
}

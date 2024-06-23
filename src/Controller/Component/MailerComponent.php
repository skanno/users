<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Mailer;

class MailerComponent extends Component
{
    public function deliver(string $subject, string $to, string $template, $vars = [])
    {
        $mailer = new Mailer('default');
        $mailer->setTo($to)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate($template)
            ->setLayout('default')
            ->setVars($vars);
        $mailer->deliver();
    }
}

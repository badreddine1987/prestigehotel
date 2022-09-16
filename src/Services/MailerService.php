<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmail(
        $to = 'contact@hotelprestige.fr',
        $subject = "L'objet du mail",
        $content = '',
        $text = ''
    ): void {
        $email = (new Email())
            ->from('noreply@hotelprestige.fr')
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->html($content);
        $this->mailer->send($email);
    }
}

<?php

namespace App\Notification;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;


class Sender
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewUserNotificationToAdmin(UserInterface $user): void
    {
//            file_put_contents('debug.txt', $user->getEmail());

        $email = (new Email())
            ->from('accounts@series.com')
            ->to('admin@admin.com')
            ->subject('new account created on series.com')
            ->html('<h1>New Account!</h1>email: '.$user->getEmail());
        $this->mailer->send($email);
    }
}
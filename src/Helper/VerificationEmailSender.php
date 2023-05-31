<?php

namespace App\Helper;

use App\Entity\Admin;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerificationEmailSender
{
    public function __construct(
        private ContainerInterface $parameterBag,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
    ) {
    }

    public function sendVerificationEmail(Admin $user)
    {
        try {
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail(),
                [
                    'id' => $user->getId()
                ]
            );

            $senderEmail = $this->parameterBag->get('senderEmail');
            $senderName = $this->parameterBag->get('senderName');

            $email = new TemplatedEmail();
            $email->from(new Address($senderEmail, $senderName));
            $email->to($user->getEmail());
            $email->subject('Please Confirm your Email');
            $email->htmlTemplate('security/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

            $this->mailer->send($email);
            return [
                'success' => true,
                'message' => 'Email sent successfully',
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}

<?php

namespace App\EntityListener;

use App\Entity\Admin;
use App\Helper\VerificationEmailSender;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminEntityListener
{
    private $enableEmailVerification = false;

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private VerificationEmailSender $verificationEmailSender,
    ) {
    }

    public function prePersist(Admin $user, LifecycleEventArgs $event)
    {
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        ));

        if (!$this->enableEmailVerification) {
            $user->setIsVerified(true);
        }
    }

    public function postPersist(Admin $user, LifecycleEventArgs $event)
    {
        if ($this->enableEmailVerification) {
            $this->verificationEmailSender->sendVerificationEmail($user);
        }
    }

    public function preUpdate(Admin $user, PreUpdateEventArgs $event)
    {
        $plainPassword = $user->getPlainPassword();
        if ($plainPassword) {
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            ));
        }
    }
}

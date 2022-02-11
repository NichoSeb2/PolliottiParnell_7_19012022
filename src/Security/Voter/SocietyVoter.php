<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Society;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SocietyVoter extends Voter {
    public const USER_OWNERSHIP = "USER_OWNERSHIP";

    public function __construct(private Security $security) {}

	protected function supports(string $attribute, $subject): bool {
        return in_array($attribute, [self::USER_OWNERSHIP])
            && $subject instanceof User;
    }

	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        $society = $token->getUser();

        if (!$society instanceof UserInterface) {
            return false;
        }

        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            case self::USER_OWNERSHIP:
                return $this->canAccess($society, $user);
        }

        return false;
    }

	private function canAccess(Society $society, User $user): bool {
		if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

		return $user->getSociety()->getId() === $society->getId();
    }
}

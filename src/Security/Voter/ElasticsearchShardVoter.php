<?php

namespace App\Security\Voter;

use App\Model\ElasticsearchNodeModel;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ElasticsearchShardVoter extends Voter
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        $attributes = [
            'SHARD_MOVE',
            'SHARD_ALLOCATE_REPLICA',
            'SHARD_CANCEL',
        ];

        return in_array($attribute, $attributes) && $subject instanceof ElasticsearchNodeModel;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return $this->security->isGranted('ROLE_ADMIN', $user);
    }
}

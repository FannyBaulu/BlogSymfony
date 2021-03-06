<?php
namespace App\Application\Security\Voter;

use App\Application\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    public const EDIT = "edit";

    
    protected function supports(string $attribute, $subject)
    {
        if (!$subject instanceof Post){
            return false;
        }
        if(!in_array($attribute,[self::EDIT])){
            return false;
        }
        return true;
    }

    /**
     * @param string $attribute
     * @param Post $subject
     * @param TokenInterface $token
     * @return void
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        switch($attribute){
            case self::EDIT :
                return $user === $subject->getUser();
                break;
        }
    }
}
<?php

namespace App\Application\Validator;

use App\Application\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        if($this->userRepository->count(["email"=>$value]) > 0)
        {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
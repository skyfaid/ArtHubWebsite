<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UtilisateursRepository;
class UniqueUserConstraintValidator extends ConstraintValidator
{
    private $utilisateursRepository;

    public function __construct(UtilisateursRepository $utilisateursRepository)
    {
        $this->utilisateursRepository = $utilisateursRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        // Check if value is null or empty
        if (null === $value || '' === $value) {
            return;
        }

       // Check for uniqueness in the database for pseudo
       $existingUserPseudo = $this->utilisateursRepository->findOneBy(['pseudo' => $value]);
       if ($existingUserPseudo) {
           // If the value already exists, add a violation to the context
           $this->context->buildViolation($constraint->message)
               ->addViolation();
           return; // No need to check further if pseudo already exists
       }

       // Check for uniqueness in the database for email
       $existingUserEmail = $this->utilisateursRepository->findOneBy(['email' => $value]);
       if ($existingUserEmail) {
           // If the value already exists, add a violation to the context
           $this->context->buildViolation($constraint->message)
               ->addViolation();
       }
   }
}

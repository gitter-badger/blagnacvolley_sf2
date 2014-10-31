<?php

namespace BV\FrontBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class TeamLeadersAreMembers
 * @package BV\FrontBundle\Validator
 * @Annotation
 */
class TeamLeadersAreMembers extends Constraint
{
    public function validatedBy()
    {
        return 'team_leaders_are_members';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

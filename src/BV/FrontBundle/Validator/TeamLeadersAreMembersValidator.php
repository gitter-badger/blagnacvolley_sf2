<?php

namespace BV\FrontBundle\Validator;

use BV\FrontBundle\Entity\Team;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class TeamLeadersAreMembersValidator extends ConstraintValidator
{
    /**
     * @param Team $team
     * @param Constraint $constraint
     */
    public function validate($team, Constraint $constraint)
    {
        switch ($team->getType()) {
            case Team::TYPE_FEM:
                $members = $team->getMembersFem();
                break;
            case Team::TYPE_MIX:
                $members = $team->getMembersMix();
                break;
            case Team::TYPE_MSC:
                $members = $team->getMembersMsc();
                break;
        }

        if (!$members->contains($team->getCaptain())) {
            $this->context
                ->buildViolation('The captain must be in the members list.')
                ->atPath('captain')
                ->addViolation()
            ;
        }
        if (!$members->contains($team->getSubCaptain())) {
            $this->context
                ->buildViolation('The subCaptain must be in the members list.')
                ->atPath('subCaptain')
                ->addViolation()
            ;
        }

    }
}

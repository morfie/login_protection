<?php

namespace Docler\UserBundle\Entity;

use Bafford\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @PasswordStrength(
     *     requireCaseDiff=true,
     *     requireNumbers=true
     * )
     *
     * @var string
     */
    protected $plainPassword;
}
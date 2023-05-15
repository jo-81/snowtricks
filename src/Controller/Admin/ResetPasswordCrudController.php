<?php

namespace App\Controller\Admin;

use App\Entity\ResetPassword;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ResetPasswordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResetPassword::class;
    }
}

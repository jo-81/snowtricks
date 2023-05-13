<?php

namespace App\Tests\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorTrait
{
    protected function assertHasErrors(object $entity, int $number = 0): void
    {
        /** @var ContainerInterface $container */
        $container = self::getContainer();

        /** @var ValidatorInterface $validator */
        $validator = $container->get('validator');

        $error = $validator->validate($entity);
        $this->assertCount($number, $error);
    }
}

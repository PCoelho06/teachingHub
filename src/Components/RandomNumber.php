<?php

namespace App\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('RandomNumber')]
class RandomNumber
{
    use DefaultActionTrait;

    public FormView $form;

    public function getRandomNumber(): int
    {
        return random_int(0, 100);
    }
}

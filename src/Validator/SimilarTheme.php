<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class SimilarTheme extends Constraint
{
    public string $message = 'The theme "{{ theme }}" is too similar to the "{{ similarTheme }}" theme.';

    public function __construct(?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}

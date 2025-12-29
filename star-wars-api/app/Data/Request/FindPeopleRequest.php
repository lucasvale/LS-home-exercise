<?php

namespace App\Data\Request;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class FindPeopleRequest extends Data
{
    public function __construct(
        public ?string $name,
    ){}

    public static function rules($context): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            '*' => [
                Rule::prohibitedIf(function () use ($context) {
                    $payload  = $context->payload;
                    $acceptable = ['name'];
                    $res = array_diff(array_keys($payload), $acceptable);
                    if (count($res) > 0) {
                        return true;
                    }
                    return false;
                }),
            ],
        ];
    }

}

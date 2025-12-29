<?php

namespace App\Data\Request;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class FindFilmsRequest extends Data
{
    public function __construct(
        public ?string $title,
    ){}

    public static function rules($context): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            '*' => [
                Rule::prohibitedIf(function () use ($context) {
                    $payload  = $context->payload;
                    $acceptable = ['title'];
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

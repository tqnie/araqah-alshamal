<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContactUsForm extends Form
{
    #[Validate('required|string')]
    public string $subject = '';

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $mobile = '';

    #[Validate('required|string')]
    public string $body = ''; 

    #[Validate('string')]
    public string $status = 'NEW';
    
}

<?php

namespace App\Rules;

use App\Models\PenyusunApplication;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailNotValidatedByAdmin implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (PenyusunApplication::isEmailValidatedByAdmin($value)) {
            $fail('Email ini sudah digunakan oleh penyusun yang telah divalidasi admin. Satu email hanya boleh digunakan untuk satu pengajuan yang disetujui.');
        }
    }
}

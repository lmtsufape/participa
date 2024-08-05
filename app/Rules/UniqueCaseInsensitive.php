<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueCaseInsensitive implements ValidationRule
{
    protected $table;
    protected $column;
    protected $exceptId;

    public function __construct($table, $column, $exceptId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->exceptId = $exceptId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)
                    ->whereRaw("LOWER({$this->column}) = LOWER(?)", [$value]);

        if ($this->exceptId) {
            $query->where('id', '!=', $this->exceptId);
        }

        if ($query->exists()) {
            $fail('validation.unique')->translate([
                'value' => $this->column,
            ]);
        }
    }
}

<?php

namespace App\Http\Requests\Api;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocationLogBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        $employee = Employee::find($this->input('employee_id'));

        if (! $employee) {
            return false;
        }

        return $employee->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'pings' => ['required', 'array', 'min:1', 'max:500'],
            'pings.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'pings.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'pings.*.recorded_at' => ['required', 'date', 'before_or_equal:now'],
            'pings.*.accuracy_meters' => ['nullable', 'integer', 'min:0'],
            'pings.*.battery_level' => ['nullable', 'integer', 'between:0,100'],
            'pings.*.place_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}

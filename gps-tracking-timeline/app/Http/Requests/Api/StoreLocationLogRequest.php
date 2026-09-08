<?php

namespace App\Http\Requests\Api;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocationLogRequest extends FormRequest
{
    /**
     * Only allow a device to submit pings for the employee record that is
     * linked to the currently authenticated user — prevents one employee's
     * device from spoofing another employee's location.
     */
    public function authorize(): bool
    {
        $employee = Employee::find($this->input('employee_id'));

        if (! $employee) {
            return false; // caught properly by the 'exists' validation rule below
        }

        return $employee->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'recorded_at' => ['required', 'date', 'before_or_equal:now'],
            'accuracy_meters' => ['nullable', 'integer', 'min:0'],
            'battery_level' => ['nullable', 'integer', 'between:0,100'],
            'place_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'recorded_at.before_or_equal' => 'recorded_at cannot be in the future.',
        ];
    }
}

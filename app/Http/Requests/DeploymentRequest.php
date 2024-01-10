<?php

namespace App\Http\Requests;

use App\Rules\MustBeBrandAmbassador;
use App\Rules\MustBeTeamLeader;
use Illuminate\Foundation\Http\FormRequest;

class DeploymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'team_id' => ['required', 'exists:teams,id'],
            'team_leader' => ['required', new MustBeTeamLeader()],
            'brand_ambassador' => ['required', 'array'],
            'brand_ambassador.*' => [new MustBeBrandAmbassador(), 'distinct'],
        ];
    }

    public function messages()
    {
        return [
            'brand_ambassador.*.distinct' => 'There must be no duplicate deployee on each deployee names'
        ];
    }
}

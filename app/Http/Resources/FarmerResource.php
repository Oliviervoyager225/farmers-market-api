<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'identifier'   => $this->identifier,
            'first_name'   => $this->firstname,
            'last_name'    => $this->lastname,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'state'        => $this->state,
            'city'         => $this->city,
            'bio'          => $this->bio,
            'categories'   => $this->categories ?? [],
            'specialties'  => $this->specialties ?? [],
            'credit_limit' => $this->credit_limit_fcfa,
            'current_debt' => $this->total_remaining_debt,
        ];
    }
}

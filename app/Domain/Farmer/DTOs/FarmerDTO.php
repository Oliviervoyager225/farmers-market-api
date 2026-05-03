<?php

declare(strict_types=1);

namespace App\Domain\Farmer\DTOs;

final readonly class FarmerDTO
{
    public function __construct(
        public string  $identifier,
        public string  $firstname,
        public string  $lastname,
        public int     $operatorId,
        public ?string $phone = null,
        public float   $creditLimitFcfa = 0,
        public ?string $email = null,
        public ?string $state = null,
        public ?string $city = null,
        public ?string $address = null,
        public ?string $bio = null,
        public ?float  $farmSize = null,
        public ?int    $experience = null,
        public ?array  $categories = null,
        public ?array  $specialties = null,
        public ?string $certification = null,
        public ?string $primaryMarket = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            identifier:      $data['identifier'] ?? '',
            firstname:       $data['firstname'],
            lastname:        $data['lastname'],
            operatorId:      isset($data['operator_id']) ? (int) $data['operator_id'] : 0,
            phone:           $data['phone'] ?? null,
            creditLimitFcfa: isset($data['credit_limit_fcfa']) ? (float) $data['credit_limit_fcfa'] : 0,
            email:           $data['email'] ?? null,
            state:           $data['state'] ?? null,
            city:            $data['city'] ?? null,
            address:         $data['address'] ?? null,
            bio:             $data['bio'] ?? null,
            farmSize:        isset($data['farm_size']) ? (float) $data['farm_size'] : null,
            experience:      isset($data['experience']) ? (int) $data['experience'] : null,
            categories:      $data['categories'] ?? null,
            specialties:     $data['specialties'] ?? null,
            certification:   $data['certification'] ?? null,
            primaryMarket:   $data['primary_market'] ?? null,
        );
    }
}

<?php

namespace App\Service;

use App\Entity\Guest;
use App\Repository\GuestRepository;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Countries;

class GuestService
{
    private GuestRepository $guestRepository;
    private PhoneNumberUtil $phoneNumberUtil;

    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }


    public function getAllGuests(): array
    {
        return $this->guestRepository->findAllGuests();
    }

    public function getGuestById(int $id): Guest
    {
        $guest = $this->guestRepository->findGuestById($id);
        if (!$guest) {
            throw new NotFoundHttpException("Guest with ID $id not found.");
        }
        return $guest;
    }

    public function createGuest(array $data): Guest
    {
        $guest = new Guest();
        $guest->setFirstName($data['firstName']);
        $guest->setLastName($data['lastName']);
        $guest->setPhone($data['phone']);
        $guest->setEmail($data['email']);
        $guest->setCountry($data['country'] ?? $this->defineCountryByPhone($guest->getPhone()));

        return $this->guestRepository->createGuest($guest);
    }

    public function updateGuest(Guest $guest, array $data): Guest
    {
        $guest->setFirstName($data['firstName'] ?? $guest->getFirstName());
        $guest->setLastName($data['lastName'] ?? $guest->getLastName());
        $guest->setPhone($data['phone'] ?? $guest->getPhone());
        $guest->setEmail($data['email'] ?? $guest->getEmail());
        $guest->setCountry($data['country'] ?? $this->defineCountryByPhone($guest->getPhone()));

        return $this->guestRepository->updateGuest($guest);
    }

    public function deleteGuest(Guest $guest): void
    {
        $this->guestRepository->deleteGuest($guest);
    }

    private function defineCountryByPhone(string $phone): ?string
    {
        try {
            $phoneNumber = $this->phoneNumberUtil->parse($phone, null);  // Разбиратся номер телефона
            $regionCode = $this->phoneNumberUtil->getRegionCodeForNumber($phoneNumber);  // Получаем код страны
            // По коду страны ищется название страны
            if ($regionCode !== null && $regionCode !== '') {
                return Countries::getName($regionCode);
            }
    
            return null;
        } catch (NumberParseException $e) {
            return null;
        }
    }

    private function toArray(Guest $guest): array
    {
        return [
            'id' => $guest->getId(),
            'firstName' => $guest->getFirstName(),
            'lastName' => $guest->getLastName(),
            'phone' => $guest->getPhone(),
            'email' => $guest->getEmail(),
            'country' => $guest->getCountry(),
        ];
    }

    public function getAllGuestsAsArray(): array
    {
        $guests = $this->getAllGuests();
        
        return array_map([$this, 'toArray'], $guests);
    }

    public function getGuestByIdAsArray(int $id): array
    {
        $guest = $this->getGuestById($id);
        return $this->toArray($guest);
    }
}

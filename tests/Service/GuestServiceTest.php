<?php

namespace App\Tests\Service;

use App\Entity\Guest;
use App\Repository\GuestRepository;
use App\Service\GuestService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuestServiceTest extends TestCase
{
    /**
     * @var GuestRepository|MockObject
     */
    private $guestRepository;

    /**
     * @var GuestService
     */
    private GuestService $guestService;

    protected function setUp(): void
    {
        $this->guestRepository = $this->createMock(GuestRepository::class);
        $this->guestService = new GuestService($this->guestRepository);
    }

    public function testCreateGuest(): void
    {
        $guestData = [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'phone' => '+1234567890',
            'email' => 'example@example.com',
            'country' => 'Russia'
        ];

        $guest = new Guest();
        $guest->setFirstName($guestData['firstName']);
        $guest->setLastName($guestData['lastName']);
        $guest->setPhone($guestData['phone']);
        $guest->setEmail($guestData['email']);
        $guest->setCountry($guestData['country']);

        $this->guestRepository
            ->expects($this->once())
            ->method('createGuest')
            ->with($this->isInstanceOf(Guest::class))
            ->willReturn($guest);

        $result = $this->guestService->createGuest($guestData);

        $this->assertInstanceOf(Guest::class, $result);
        $this->assertEquals('Ivan', $result->getFirstName());
        $this->assertEquals('Ivanov', $result->getLastName());
        $this->assertEquals('Russia', $result->getCountry());
    }

    public function testUpdateGuest(): void
    {
        $guest = new Guest();
        $guest->setFirstName('Fedor');
        $guest->setLastName('Fedorov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('john.doe@example.com');

        $updatedData = [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'phone' => '+9876543210',
            'email' => 'example@example.com'
        ];

        $this->guestRepository
            ->expects($this->once())
            ->method('updateGuest')
            ->with($this->isInstanceOf(Guest::class))
            ->willReturn($guest);

        $result = $this->guestService->updateGuest($guest, $updatedData);

        $this->assertEquals('Ivan', $result->getFirstName());
        $this->assertEquals('Ivanov', $result->getLastName());
    }

    public function testGetAllGuests(): void
    {
        $guest = new Guest();
        $guest->setFirstName('Ivan');
        $guest->setLastName('Ivanov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('example@example.com');

        $this->guestRepository
            ->expects($this->once())
            ->method('findAllGuests')
            ->willReturn([$guest]);

        $guests = $this->guestService->getAllGuests();

        $this->assertCount(1, $guests);
        $this->assertEquals('Ivan', $guests[0]->getFirstName());
    }

    public function testDeleteGuest(): void
    {
        $guest = new Guest();
        $this->guestRepository
            ->expects($this->once())
            ->method('deleteGuest')
            ->with($guest);

        $this->guestService->deleteGuest($guest);
    }

    public function testGetGuestById(): void
    {
        $guest = new Guest();
        $guest->setFirstName('Ivan');
        $guest->setLastName('Ivanov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('example@example.com');

        $this->guestRepository
            ->expects($this->once())
            ->method('findGuestById')
            ->with(1)
            ->willReturn($guest);

        $result = $this->guestService->getGuestById(1);

        $this->assertInstanceOf(Guest::class, $result);
        $this->assertEquals('Ivan', $result->getFirstName());
        $this->assertEquals('Ivanov', $result->getLastName());
    }

    public function testGetGuestByIdNotFound(): void
    {
        $this->guestRepository
            ->expects($this->once())
            ->method('findGuestById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->guestService->getGuestById(1);
    }
}

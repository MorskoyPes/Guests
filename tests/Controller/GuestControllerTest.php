<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\GuestRepository;
use App\Entity\Guest;

class GuestControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateGuest(): void
    {
        $guestRepository = $this->createMock(GuestRepository::class);
        
        $guest = new Guest();
        $guest->setFirstName('Ivan');
        $guest->setLastName('Ivanov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('example@example.com');

        $guestRepository
            ->expects($this->once())
            ->method('createGuest')
            ->willReturn($guest);

        $this->client->getContainer()->set(GuestRepository::class, $guestRepository);

        $this->client->request('POST', '/guest/new', [], [], [], json_encode([
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'phone' => '+1234567890',
            'email' => 'example@example.com'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetGuest(): void
    {
        $guestRepository = $this->createMock(GuestRepository::class);

        $guest = new Guest();
        $guest->setFirstName('Ivan');
        $guest->setLastName('Ivanov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('example@example.com');

        $guestRepository
            ->expects($this->once())
            ->method('findGuestById')
            ->with(1)
            ->willReturn($guest);

        $this->client->getContainer()->set(GuestRepository::class, $guestRepository);

        $this->client->request('GET', '/guest/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $responseContent = $this->client->getResponse()->getContent();
        $guestData = json_decode($responseContent, true);

        $this->assertEquals('Ivan', $guestData['firstName']);
        $this->assertEquals('Ivanov', $guestData['lastName']);
        $this->assertEquals('+1234567890', $guestData['phone']);
        $this->assertEquals('example@example.com', $guestData['email']);
    }

    public function testGetAllGuests(): void
    {
        $guestRepository = $this->createMock(GuestRepository::class);

        $guest1 = new Guest();
        $guest1->setFirstName('Ivan');
        $guest1->setLastName('Ivanov');
        $guest1->setPhone('+1234567890');
        $guest1->setEmail('example@example.com');

        $guest2 = new Guest();
        $guest2->setFirstName('Fedor');
        $guest2->setLastName('Fedorov');
        $guest2->setPhone('+9876543210');
        $guest2->setEmail('example2@example.com');

        $guestRepository
            ->expects($this->once())
            ->method('findAllGuests')
            ->willReturn([$guest1, $guest2]);

        $this->client->getContainer()->set(GuestRepository::class, $guestRepository);

        $this->client->request('GET', '/guest');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $responseContent = $this->client->getResponse()->getContent();
        $guestsData = json_decode($responseContent, true);

        $this->assertCount(2, $guestsData);
        $this->assertEquals('Ivan', $guestsData[0]['firstName']);
        $this->assertEquals('Ivanov', $guestsData[0]['lastName']);
        $this->assertEquals('Fedor', $guestsData[1]['firstName']);
        $this->assertEquals('Fedorov', $guestsData[1]['lastName']);
    }

    public function testDeleteGuest(): void
    {
        $guestRepository = $this->createMock(GuestRepository::class);

        $guest = new Guest();
        $guest->setFirstName('Ivan');
        $guest->setLastName('Ivanov');
        $guest->setPhone('+1234567890');
        $guest->setEmail('example@example.com');

        $guestRepository
            ->expects($this->once())
            ->method('findGuestById')
            ->with(1)
            ->willReturn($guest);

        $guestRepository
            ->expects($this->once())
            ->method('deleteGuest')
            ->with($guest);

        $this->client->getContainer()->set(GuestRepository::class, $guestRepository);

        $this->client->request('DELETE', '/guest/1', [], [], [], json_encode(['_token' => 'valid_token']));

        $this->assertResponseStatusCodeSame(204);
    }
}

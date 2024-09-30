<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Guest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Guest::class);
        $this->entityManager = $entityManager;
    }

    public function findGuestById(int $id): ?Guest
    {
        return $this->find($id);
    }

    public function findAllGuests(): array
    {
        return $this->findAll();
    }

    public function createGuest(Guest $guest): Guest
    {
        $this->entityManager->persist($guest);
        $this->entityManager->flush();

        return $guest;
    }

    public function updateGuest(Guest $guest): Guest
    {
        $this->entityManager->flush();

        return $guest;
    }

    public function deleteGuest(Guest $guest): void
    {
        $this->entityManager->remove($guest);
        $this->entityManager->flush();
    }
}

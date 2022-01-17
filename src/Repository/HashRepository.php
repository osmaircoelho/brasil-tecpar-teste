<?php

namespace App\Repository;

use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\AbstractQuery;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    public $doctrine;

    public function __construct( ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        $this->doctrine = $registry;
        parent::__construct($registry, Hash::class);
    }

    public function save(array $data)
    {

        $entityManager = $this->doctrine->getManager();

        $hash = new Hash();

        $hash->setBatch( $data['batch'] );
        $hash->setStringInput( $data['string'] );
        $hash->setKeyFound( $data['key'] );
        $hash->setHashGenerated($data['hash']);
        $hash->setNumberAttempts( $data['attempts'] );

        $entityManager->persist($hash);
        return $entityManager->flush();
    }

    public function tryFindHashByInput(string $string): array
    {
        return $this->createQueryBuilder('h')
            ->Select(['h.id','h.string_input','h.hash_generated'])
            ->Where('h.string_input = :val')
            ->setParameter('val', $string)
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllFilterPaginate(int $numberAttemptsFilter = 0)
    {
        if( $numberAttemptsFilter > 0 ){
           $qb = $this->createQueryBuilder('h')
                ->Select(['h.batch','h.id','h.string_input', 'h.key_found', 'h.number_attempts']);
           $qb->Where('h.number_attempts <= :val')->setParameter('val', $numberAttemptsFilter);

           return $qb->getQuery()->getArrayResult();
        }

        return $this->findAllPaginate();

    }

    public function findAllPaginate(){

       return $this->createQueryBuilder('h')
            ->select(['h.batch','h.id','h.string_input', 'h.key_found'])
            ->getQuery()
            ->getArrayResult()
       ;
    }

}

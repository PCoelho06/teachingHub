<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findBySearchCriteria($criteria): array
    {
        $result = $this->createQueryBuilder('d');

        $searchedTypes = $criteria['type'];
        if (!is_null($searchedTypes)) {
            foreach ($searchedTypes as $type) {
                if ($type === reset($searchedTypes)) {
                    $result->andWhere('d.type = :type');
                } else {
                    $result->orWhere('d.type = :type');
                }
                $result->setParameter('type', $type);
            }
        }

        if (!is_null($criteria['level'])) {
            $result->andWhere($result->expr()->isMemberOf(':levels', 'd.levels'))
                ->setParameter('levels', $criteria['level']);
        }

        if (!is_null($criteria['subject'])) {
            $result->andWhere($result->expr()->isMemberOf(':subjects', 'd.subjects'))
                ->setParameter('subjects', $criteria['subject']);
        }

        if (!is_null($criteria['theme'])) {
            $result->andWhere($result->expr()->isMemberOf(':themes', 'd.themes'))
                ->setParameter('themes', $criteria['theme']);
        }

        if (!is_null($criteria['title'])) {
            $result->andWhere($result->expr()->like('LOWER(d.title)', ':title'))
                ->setParameter('title', "%" . strtolower($criteria['title']) . "%");
        }

        return $result->orderBy('d.uploadedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByRating($rating)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.ratingAverage >= :val')
            ->setParameter('val', $rating)
            ->orderBy('d.uploadedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findSuggestions(Document $document)
    {
        $result = $this->createQueryBuilder('d');

        $result->andWhere('d.type = :type')
            ->setParameter('type', $document->getType());

        $result->andWhere($result->expr()->isMemberOf(':levels', 'd.levels'))
            ->setParameter('levels', $document->getLevels());

        $result->andWhere($result->expr()->isMemberOf(':subjects', 'd.subjects'))
            ->setParameter('subjects', $document->getSubjects());

        $result->andWhere($result->expr()->isMemberOf(':themes', 'd.themes'))
            ->setParameter('themes', $document->getThemes());

        $result->orderBy('d.ratingAverage', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();


        return $result;
    }

    //    /**
    //     * @return Document[] Returns an array of Document objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Document
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

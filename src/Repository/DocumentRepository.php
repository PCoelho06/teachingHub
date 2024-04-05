<?php

namespace App\Repository;

use App\Data\SearchFilters;
use App\Entity\Document;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
    public const DOCUMENTS_PER_PAGE = 8;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    // public function findBySearchCriteria(array $criteria, int $offset): Paginator
    public function findBySearchCriteria(SearchFilters $filters, int $offset): Paginator
    {
        $query = $this->createQueryBuilder('d');

        // $searchedTypes = $criteria['type'];
        // $searchedTypes = $filters->getType();
        // if (!is_null($searchedTypes)) {
        //     foreach ($searchedTypes as $type) {
        //         if ($type === reset($searchedTypes)) {
        //             $query->andWhere('d.type = :type');
        //         } else {
        //             $query->orWhere('d.type = :type');
        //         }
        //         $query->setParameter('type', $type);
        //     }
        // }

        if (!is_null($filters->getType())) {
            $query->andWhere('d.type = :type');
            $query->setParameter('type', $filters->getType());
        }

        // if (!is_null($criteria['level'])) {
        if (!is_null($filters->getLevel())) {
            $query->andWhere($query->expr()->isMemberOf(':levels', 'd.levels'))
                // ->setParameter('levels', $criteria['level']);
                ->setParameter('levels', $filters->getLevel());
        }

        // if (!is_null($criteria['subject'])) {
        if (!is_null($filters->getSubject())) {
            $query->andWhere($query->expr()->isMemberOf(':subjects', 'd.subjects'))
                // ->setParameter('subjects', $criteria['subject']);
                ->setParameter('subjects', $filters->getSubject());
        }

        // if (!is_null($criteria['theme'])) {
        if (!is_null($filters->getTheme())) {
            $query->andWhere($query->expr()->isMemberOf(':themes', 'd.themes'))
                // ->setParameter('themes', $criteria['theme']);
                ->setParameter('themes', $filters->getTheme());
        }

        // if (!is_null($criteria['title'])) {
        if (!is_null($filters->getTitle())) {
            $query->andWhere($query->expr()->like('LOWER(d.title)', ':title'))
                // ->setParameter('title', "%" . strtolower($criteria['title']) . "%");
                ->setParameter('title', "%" . strtolower($filters->getTitle()) . "%");
        }

        $direction = ($filters->getOrderBy() === 'title') ? 'ASC' : 'DESC';

        $query->orderBy('d.' . $filters->getOrderBy(), $direction)
            ->setMaxResults(self::DOCUMENTS_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        return new Paginator($query);
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

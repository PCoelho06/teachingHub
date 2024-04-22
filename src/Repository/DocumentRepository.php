<?php

namespace App\Repository;

use App\Data\SearchFilters;
use App\Entity\Document;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Parameter;

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
    public const DOCUMENTS_PER_PAGE = 12;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findBySearchCriteria(SearchFilters $filters, int $offset): Paginator
    {
        $query = $this->createQueryBuilder('d');

        if (!is_null($filters->getType())) {
            $query->andWhere('d.type = :type');
            $query->setParameter('type', $filters->getType());
        }

        if (!is_null($filters->getLevel())) {
            $query->andWhere($query->expr()->isMemberOf(':levels', 'd.levels'))
                ->setParameter('levels', $filters->getLevel());
        }

        if (!is_null($filters->getSubject())) {
            $query->andWhere($query->expr()->isMemberOf(':subjects', 'd.subjects'))
                ->setParameter('subjects', $filters->getSubject());
        }

        if (!is_null($filters->getTheme())) {
            $query->andWhere($query->expr()->isMemberOf(':themes', 'd.themes'))
                ->setParameter('themes', $filters->getTheme());
        }

        if (!is_null($filters->getTitle())) {
            $query->andWhere($query->expr()->like('LOWER(d.title)', ':title'))
                ->setParameter('title', "%" . strtolower($filters->getTitle()) . "%");
        }

        if (!is_null($filters->getAuthor())) {
            $query->andWhere('d.author = :author');
            $query->setParameter('author', $filters->getAuthor());
        }

        if (!is_null($filters->getRatingAverage())) {
            $query->andWhere('d.ratingAverage >= :rating');
            $query->setParameter('rating', $filters->getRatingAverage());
        }

        $direction = ($filters->getOrderBy() === 'title') ? 'ASC' : 'DESC';

        $query->orderBy('d.' . $filters->getOrderBy(), $direction)
            ->setMaxResults(self::DOCUMENTS_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        return new Paginator($query);
    }

    public function findSuggestions(Document $document)
    {
        $params = new ArrayCollection();

        $result = $this->createQueryBuilder('d');

        $result->andWhere('d.id != :id');
        $params->add(new Parameter('id', $document->getId()));

        if (count($document->getLevels()) > 1) {
            $array = $this->orCondition($document->getLevels(), 'levels', $params);
            $params = $array[1];
            $result->andWhere($array[0]);
        } else {
            $result->andWhere($result->expr()->isMemberOf(':level', 'd.levels'));
            $params->add(new Parameter('level', $document->getLevels()->toArray()[0]));
        }

        if (count($document->getSubjects()) > 1) {
            $array = $this->orCondition($document->getSubjects(), 'subjects', $params);
            $params = $array[1];
            $result->andWhere($array[0]);
        } else {
            $result->andWhere($result->expr()->isMemberOf(':subject', 'd.subjects'));
            $params->add(new Parameter('subject', $document->getSubjects()->toArray()[0]));
        }

        if (count($document->getThemes()) > 1) {
            $array = $this->orCondition($document->getThemes(), 'themes', $params);
            $params = $array[1];
            $result->andWhere($array[0]);
        } else {
            $result->andWhere($result->expr()->isMemberOf(':theme', 'd.themes'));
            $params->add(new Parameter('theme', $document->getThemes()->toArray()[0]));
        }

        dump($params);

        return $result->setParameters($params)
            ->orderBy('d.ratingAverage', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    public function findBySameAuthor(Document $document)
    {
        $result = $this->createQueryBuilder('d');

        $result->andWhere('d.id != :id')
            ->andWhere('d.author = :author')
            ->setParameters(
                new ArrayCollection([
                    new Parameter('id', $document->getId()),
                    new Parameter('author', $document->getAuthor())
                ])
            );

        return $result->orderBy('d.ratingAverage', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    public function countDownloadNumber()
    {
        return $this->createQueryBuilder('d')
            ->select('SUM(d.downloadsNumber) AS nbDownloads')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopDownloadsDocuments()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.downloadsNumber', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findTopRatingsDocuments()
    {
        return $this->createQueryBuilder('d')
            ->addOrderBy('d.ratingAverage', 'DESC')
            ->addOrderBy('d.downloadsNumber', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    private function orCondition(Collection $objects, string $table, ArrayCollection $params): array
    {
        $nbObjects = count($objects);
        $sql = '';
        foreach ($objects as $key => $object) {
            $sql .= ':' . $table . '_' . $key . ' MEMBER OF d.' . $table;
            if ($key !== $nbObjects - 1) {
                $sql .= ' OR ';
            }
            $params->add(new Parameter($table . '_' . $key, $object));
        }
        return [$sql, $params];
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

<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Mosque;
use Doctrine\ORM\Query\Expr\Join;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends SortableRepository
{
    
    /**
     * @param Mosque $mosque
     * @return array
     */
    function getMessagesByMosque(Mosque $mosque) {
        $qb = $this->createQueryBuilder("mes")
                ->select("mes.title, mes.content, mes.image")
                ->innerJoin("mes.mosque", "mos", Join::WITH, "mes.mosque = :mosqueId")
                ->where("mes.enabled = 1")
                ->andWhere("mes.content IS NOT NULL OR mes.image is NOT NULL")
                ->orderBy("mes.position", "ASC")
                ->setParameter(":mosqueId", $mosque->getId());

        return $qb->getQuery()->getResult();
    }
}
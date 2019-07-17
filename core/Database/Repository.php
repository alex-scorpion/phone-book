<?php

namespace Core\Database;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;

abstract class Repository
{
    /** @var string $classModel */
    protected $classModel;
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;

    public function __construct()
    {
        if (class_exists($this->classModel)) {
            $this->em = container()->get(EntityManagerInterface::class);
            $this->repository = $this->em->getRepository($this->classModel);
        } else {
            throw new Exceptions\RepositoryException("Error create repository");
        }
    }
}

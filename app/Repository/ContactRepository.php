<?php

namespace App\Repository;

use Core\Database\Repository;
use App\Models\Contact;

class ContactRepository extends Repository
{
    protected $classModel = Contact::class;

    public function findContact($id)
    {
        return $this->repository->find($id);
    }

    public function findContacts($page, $count, string $search = "")
    {
        $offset = ($page - 1) * $count;
        $search = $search ? "%$search%" : "%";

        $dql = "SELECT c, p 
                FROM App\Models\Contact c 
                JOIN c.phones p 
                WHERE c.name LIKE :search OR 
                p.phone LIKE :search 
                ORDER BY c.name";
        $query = $this->em->createQuery($dql);
        $query->setParameter('search', $search);
        $query->setMaxResults($count);
        $query->setFirstResult($offset);

        return $query->getResult();
    }

    public function findContactsCount(string $search = "")
    {
        $search = $search ? "%$search%" : "%";
        $dql = "SELECT count(c) 
                FROM App\Models\Contact c 
                JOIN c.phones p 
                WHERE c.name LIKE :search OR 
                p.phone LIKE :search";
        $count = $this->em->createQuery($dql)
            ->setParameter('search', $search)
            ->getSingleScalarResult();

        return (int)$count;
    }

    public function createOrUpdateContact(Contact $contact, array $phones = []): Contact
    {
        $this->em->persist($contact);
        foreach ($phones as $phone) {
            $this->em->persist($phone);
        }
        $this->em->flush();

        return $contact;
    }

    public function delete(Contact $contact)
    {
        $this->em->remove($contact);
        $this->em->flush();
    }
}

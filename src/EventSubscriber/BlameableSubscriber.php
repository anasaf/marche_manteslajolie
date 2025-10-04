<?php
namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Interfaces\BlameableInterface;

class BlameableSubscriber implements EventSubscriber
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents(): array
    {
        return [ Events::prePersist, Events::preUpdate ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->applyBlame($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->applyBlame($args);
    }

    private function applyBlame(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof BlameableInterface) return;

        $token = $this->tokenStorage->getToken();
        if (!$token) return;
        $user = $token->getUser();
        if (!is_object($user)) return;

        if (method_exists($entity, 'setUpdatedBy')) {
            $entity->setUpdatedBy($user);
        }
        if (method_exists($entity, 'getCreatedBy') && method_exists($entity, 'setCreatedBy')) {
            if (null === $entity->getCreatedBy()) {
                $entity->setCreatedBy($user);
            }
        }
    }
}

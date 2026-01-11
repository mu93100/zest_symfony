<?php

namespace App\EventListener;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaMultipleUploadSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['handleMultipleUpload'],
        ];
    }

    public function handleMultipleUpload(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Media) {
            return;
        }

        $files = $entity->getFiles();

        if (empty($files)) {
            return;
        }

        foreach ($files as $uploadedFile) {
            if (!$uploadedFile instanceof UploadedFile) {
                continue;
            }

            $media = new Media();
            $media->setFile($uploadedFile);
            $media->setRole('photo_supplementaire');
            $media->setPage($entity->getPage());

            // Copier les relations
            $media->setRecette($entity->getRecette());
            $media->setProduit($entity->getProduit());
            $media->setProducteurice($entity->getProducteurice());
            $media->setRessource($entity->getRessource());

            $this->em->persist($media);
        }

        // Empêcher l’entité “multiple” d’être persistée
        $entity->setFiles([]);
    }
}

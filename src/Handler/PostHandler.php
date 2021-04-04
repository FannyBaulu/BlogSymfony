<?php

namespace App\Handler;

use App\Form\PostType;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class PostHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;

    private UploaderInterface $uploader;

    public function __construct(EntityManagerInterface $entityManager, UploaderInterface $uploader)
    {
        $this->entityManager = $entityManager;
        $this->uploader = $uploader;
    }

    protected function getFormType(): string
    {
        return PostType::class;
    }

    protected function process($data): void
    {
        $file = $this->form->get("file")->getData();

        if($file!== null){
            $data->setImage($this->uploader->upload($file));
        }

        if($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW){
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }
}
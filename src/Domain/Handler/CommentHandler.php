<?php

namespace App\Domain\Handler;

use App\Domain\Form\CommentType;
use App\Infrastructure\Handler\AbstractHandler;
use Doctrine\ORM\EntityManagerInterface;

class CommentHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getFormType(): string
    {
        return CommentType::class;
    }

    protected function process($data): void
    {
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

}
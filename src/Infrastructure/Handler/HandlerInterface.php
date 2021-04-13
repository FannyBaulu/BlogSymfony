<?php

namespace App\Infrastructure\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface HandlerInterface
 * @package App\Infrastructure\Handler
 */
interface HandlerInterface {

    /**
     * @param Request $request
     * @param mixed $data
     * @return bool
     */
    public function handle(Request $request, $data, array $options = []): bool;

    public function createView(): FormView;

}
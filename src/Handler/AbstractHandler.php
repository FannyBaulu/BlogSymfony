<?php

namespace App\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractHandler
 * @package App\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{

    private FormFactoryInterface $formFactory;

    abstract protected function getFormType(): string;

    abstract protected function process($data):void;

    protected FormInterface $form;

    public function handle(Request $request, $data, array $options = []): bool
    {
        $this->form = $this->formFactory->create($this->getFormType(),$data)->handleRequest($request);

        if ($this->form->isSubmitted()&&$this->form->isValid())
        {
            $this->process($data);
            return true;
        }

        return false;
    }

    /**
     * @required
     * @param FormFactoryInterface $formFactory
     * @return void
     */
    public function setFormFactory(FormFactoryInterface $formFactory):void
    {
        $this->formFactory = $formFactory;
    }

    public function createView(): FormView
    {
        return $this->form->createView();
    }
}
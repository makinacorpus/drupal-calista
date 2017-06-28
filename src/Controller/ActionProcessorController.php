<?php

namespace MakinaCorpus\Drupal\Calista\Controller;

use Drupal\Core\Form\FormBuilderInterface;
use MakinaCorpus\Drupal\Calista\Action\ProcessorActionProvider;
use MakinaCorpus\Drupal\Sf\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action processor controller
 */
class ActionProcessorController extends Controller
{
    /**
     * @return FormBuilderInterface
     */
    private function getFormBuilder()
    {
        return $this->get('form_builder');
    }

    /**
     * @return ProcessorActionProvider
     */
    private function getActionProcessorRegistry()
    {
        return $this->get('calista.processor_registry');
    }

    public function processAction(Request $request)
    {
        if (!$request->query->has('item')) {
            throw $this->createNotFoundException();
        }
        if (!$request->query->has('processor')) {
            throw $this->createNotFoundException();
        }

        try {
            $processor = $this
                ->getActionProcessorRegistry()
                ->get($request->query->get('processor'))
            ;
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }

        $item = $processor->loadItem($request->query->get('item'));
        if (!$item) {
            throw $this->createNotFoundException();
        }
        if (!$processor->appliesTo($item)) {
            throw $this->createAccessDeniedException();
        }

        return $this->getFormBuilder()->getForm($processor->getFormClass(), $processor, $item);
    }
}

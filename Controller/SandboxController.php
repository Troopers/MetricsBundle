<?php

namespace Troopers\MetricsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troopers\MetricsBundle\Form\Type\LogType;
use Troopers\MetricsBundle\Model\Log;

/**
 * Class SandboxController
 * @Route("/sandbox")
 *
 * @package Troopers\MetricsBundle\Controller
 */
class SandboxController extends Controller
{
    /**
     * @param Request $request
     * @Template
     * @Route("/newLog", name="troopers_metrics_sandbox_newLog")
     * @Method({"GET", "POST"})
     * @return array
     */
    public function newLogAction(Request $request)
    {
        $log = new Log();
        $logForm = $this->createLogForm($log);
        if ($request->isMethod(Request::METHOD_POST)) {
            $logForm->handleRequest($request);
            if ($logForm->isValid()) {
                if ($log->getDatetime() instanceof \DateTime) {
                    $log->addContext('@datetime', $log->getDatetime());
                }
                $this->get('logger')->log($log->getLevel(), $log->getMessage(), $log->getContext());
                $this->get('session')->getFlashBag()->add('success', 'log sent');

                return $this->redirectToRoute('troopers_metrics_sandbox_newLog');
            }
        }

        return [
            'form' => $logForm->createView(),
        ];
    }

    /**
     * Creates a form to choose the Admin\TalentColumn entity.
     *
     * @param Log $log
     *
     * @return \Symfony\Component\Form\Form The form
     * @internal param Request $request
     *
     */
    private function createLogForm(Log $log)
    {
        $form = $this->createForm(LogType::class, $log, [
            'action' => $this->generateUrl('troopers_metrics_sandbox_newLog'),
            'method' => 'POST',
        ]);

        return $form;
    }
}

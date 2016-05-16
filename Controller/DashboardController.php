<?php

namespace Troopers\MetricsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troopers\MetricsBundle\Entity\Dashboard;
use Troopers\MetricsBundle\Entity\Dashboard\TimeFilter;
use Troopers\MetricsBundle\Form\Type\TimeFilterType;

/**
 * Class DashboardController
 * @Route("/dashboard")
 *
 * @package Troopers\MetricsBundle\Controller
 */
class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @Template
     * @Route("/{id}", name="troopers_metrics_dashboard_show")
     * @Method({"GET"})
     * @return array
     */
    public function showAction(Request $request, Dashboard $dashboard)
    {
        $timeFilter = new TimeFilter();
        $timeFilterForm = $this->createTimeFilterForm($timeFilter, $dashboard);
        $timeFilterForm->handleRequest($request);
        $dashboard->handleTimeFilter($timeFilter);

        return [
            'dashboard' => $dashboard,
            'timeFilter' => $timeFilter,
            'timeFilterForm' => $timeFilterForm->createView(),
        ];
    }

    /**
     * Creates a form to choose the Admin\TalentColumn entity.
     *
     * @param TimeFilter $timeFilter
     *
     * @return \Symfony\Component\Form\Form The form
     * @internal param Request $request
     *
     */
    private function createTimeFilterForm(TimeFilter $timeFilter, Dashboard $dashboard)
    {
        $form = $this->createForm(TimeFilterType::class, $timeFilter, [
            'action' => $this->generateUrl('troopers_metrics_dashboard_show', ['id' => $dashboard->getId()]),
            'method' => 'GET',
        ]);

        return $form;
    }
}

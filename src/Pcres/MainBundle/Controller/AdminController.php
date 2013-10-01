<?php

namespace Pcres\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pcres\MainBundle\Form\Pc;
use Pcres\MainBundle\Form\PcArea;

class AdminController extends Controller
{
    public function indexAction()
    {
        $formPc = $this->createForm(new Pc($this->getDoctrine()));

        $formPcArea = $this->createForm(new PcArea($this->getDoctrine()));

        $repository = $this->getDoctrine()->getRepository('PcresMainBundle:Pc');
        $pcs = $repository->findAll();

        $repository2 = $this->getDoctrine()->getRepository('PcresMainBundle:PcArea');
        $pcAreas = $repository2->findAll();

        return $this->render(
            'PcresMainBundle:Admin:index.html.twig',
            array('pcs' => $pcs, 'pcAreas' => $pcAreas, 'formPc' => $formPc->createView(), 'formPcArea' => $formPcArea->createView())
        );
    }
    public function pcAction()
    {
        $request = $this->getRequest();
        $id = $request->query->get('urlData');

        $formPc = $this->createForm(new Pc($this->getDoctrine()));
        return $this->render(
            'PcresMainBundle:Admin:pc.html.twig',
            array('formPc' => $formPc->createView())
        );


        return $this->render(
            'PcresMainBundle:Admin:index.html.twig',
            array('pcs' => $pcs)
        );
    }

}
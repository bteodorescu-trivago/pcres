<?php

namespace Pcres\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
         return $this->render('PcresMainBundle:Welcome:index.html.twig');
    }
}
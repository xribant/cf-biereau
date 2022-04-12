<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontPageEventController extends AbstractController
{
    #[Route('/evenement/{event}/details', name: 'event_details_page')]
    public function index(): Response
    {

        return $this->render('front_page_event/index.html.twig');
    }
}

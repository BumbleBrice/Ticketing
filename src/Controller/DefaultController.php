<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\SpectacleRepository;
use App\Service\Weezevent;
use App\Entity\Spectacle;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(SpectacleRepository $spectacleRepository)
    {        
        return $this->render('default/index.html.twig');
    }
}

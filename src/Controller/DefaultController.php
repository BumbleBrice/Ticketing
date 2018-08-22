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

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contacts()
    {
        return $this->render('default/contacts.html.twig');
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function cgv()
    {
        return $this->render('default/cgv.html.twig');
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu()
    {
        return $this->render('default/cgu.html.twig');
    }

    /**
     * @Route("/mentionslegales", name="mentions")
     */
    public function mentions()
    {
        return $this->render('default/mentions.html.twig');
    }
}

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
        $weezevent = new Weezevent('collilieux.brice@gmail.com', 'bumbleDev&2018', 'accbf05c0bc82872681e3c63eb9d0d4d');

        $test = $weezevent->getEventDetails('364030');

        $events = $weezevent->getEvents([
            'include_not_published' => true,    
            'include_closed'        => true,
            'include_without_sales' => true]);

        foreach($events->events as $event)
        {
            $eventDetail = $weezevent->getEventDetails($event->id);

            $spectacle = $spectacleRepository->findOneBy(['weezevent_id' => $event->id]);

            if($spectacle === null)
            {
                $spectacle = new Spectacle();
                $spectacle->setNom($event->name);
                
                $date = new \DateTime($event->date->start);

                $spectacle->setDate($date);
                $spectacle->setPlaces($event->participants);
                $spectacle->setDescription($eventDetail->events->description);
                $spectacle->setWeezeventId($event->id);
                $spectacle->setLastUpdate($eventDetail->last_update);

                $em = $this->getDoctrine()->getManager();
                $em->persist($spectacle);
                $em->flush();
            }
            if($spectacle !== null && $spectacle->getLastUpdate() !== $eventDetail->last_update){
                $spectacle->setNom($event->name);
                
                $date = new \DateTime($event->date->start);

                $spectacle->setDate($date);
                $spectacle->setPlaces($event->participants);
                $spectacle->setDescription($eventDetail->events->description);
                $spectacle->setWeezeventId($event->id);
                $spectacle->setLastUpdate($eventDetail->last_update);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }

        }


        // $spectacle = new Spectacle();

        // $spectacle->setNom();

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($spectacle);
        // $em->flush();

        return $this->render('default/index.html.twig', ['test' => $events]);
    }
}

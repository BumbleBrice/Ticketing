<?php

namespace App\Controller;

use App\Entity\Spectacle;
use App\Service\Weezevent;
use App\Form\SpectacleType;
use App\Repository\SpectacleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/spectacle")
 */
class SpectacleController extends Controller
{
    /**
     * @Route("/", name="spectacle_index", methods="GET")
     */
    public function index(SpectacleRepository $spectacleRepository): Response
    {
        $weezevent = new Weezevent('collilieux.brice@gmail.com', 'bumbleDev&2018', 'accbf05c0bc82872681e3c63eb9d0d4d');

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
                $spectacle->setPicture($eventDetail->events->image);

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
                $spectacle->setPicture($eventDetail->events->image);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }
        
        return $this->render('spectacle/index.html.twig', ['spectacles' => $spectacleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="spectacle_new", methods="GET|POST")
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): Response
    {
        $spectacle = new Spectacle();
        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($spectacle);
            $em->flush();

            return $this->redirectToRoute('spectacle_index');
        }

        return $this->render('spectacle/new.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spectacle_show", methods="GET")
     */
    public function show(Spectacle $spectacleShow, SpectacleRepository $spectacleRepository): Response
    {
        $weezevent = new Weezevent('collilieux.brice@gmail.com', 'bumbleDev&2018', 'accbf05c0bc82872681e3c63eb9d0d4d');

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
                $spectacle->setPicture($eventDetail->events->image);

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
                $spectacle->setPicture($eventDetail->events->image);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }

        return $this->render('spectacle/show.html.twig', ['spectacle' => $spectacleShow]);
    }

    /**
     * @Route("/{id}/edit", name="spectacle_edit", methods="GET|POST")
     */
    public function edit(Request $request, Spectacle $spectacle): Response
    {
        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('spectacle_edit', ['id' => $spectacle->getId()]);
        }

        return $this->render('spectacle/edit.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spectacle_delete", methods="DELETE")
     */
    public function delete(Request $request, Spectacle $spectacle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$spectacle->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($spectacle);
            $em->flush();
        }

        return $this->redirectToRoute('spectacle_index');
    }
}

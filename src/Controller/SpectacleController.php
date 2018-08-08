<?php

namespace App\Controller;

use App\Entity\Spectacle;
use App\Service\Weezevent;
use App\Service\RefreshEvents;
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
    public function index(SpectacleRepository $spectacleRepository, RefreshEvents $RefreshEvents): Response
    {
        $RefreshEvents->refresh($spectacleRepository, $this->getDoctrine()->getManager());

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
    public function show(Spectacle $spectacleShow, SpectacleRepository $spectacleRepository, RefreshEvents $RefreshEvents): Response
    {
        $RefreshEvents->refresh($spectacleRepository, $this->getDoctrine()->getManager());

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

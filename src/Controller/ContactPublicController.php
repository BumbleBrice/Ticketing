<?php

namespace App\Controller;

use App\Entity\ContactPublic;
use App\Form\ContactPublicType;
use App\Repository\ContactPublicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact/public")
 */
class ContactPublicController extends Controller
{
    /**
     * @Route("/", name="contact_public_index", methods="GET")
     */
    public function index(ContactPublicRepository $contactPublicRepository): Response
    {
        return $this->render('contact_public/index.html.twig', ['contact_publics' => $contactPublicRepository->findAll()]);
    }

    /**
     * @Route("/new", name="contact_public_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $contactPublic = new ContactPublic();
        $form = $this->createForm(ContactPublicType::class, $contactPublic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactPublic);
            $em->flush();

            return $this->redirectToRoute('contact_public_index');
        }

        return $this->render('contact_public/new.html.twig', [
            'contact_public' => $contactPublic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_public_show", methods="GET")
     */
    public function show(ContactPublic $contactPublic): Response
    {
        return $this->render('contact_public/show.html.twig', ['contact_public' => $contactPublic]);
    }

    /**
     * @Route("/{id}/edit", name="contact_public_edit", methods="GET|POST")
     */
    public function edit(Request $request, ContactPublic $contactPublic): Response
    {
        $form = $this->createForm(ContactPublicType::class, $contactPublic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_public_edit', ['id' => $contactPublic->getId()]);
        }

        return $this->render('contact_public/edit.html.twig', [
            'contact_public' => $contactPublic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_public_delete", methods="DELETE")
     */
    public function delete(Request $request, ContactPublic $contactPublic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactPublic->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contactPublic);
            $em->flush();
        }

        return $this->redirectToRoute('contact_public_index');
    }
}

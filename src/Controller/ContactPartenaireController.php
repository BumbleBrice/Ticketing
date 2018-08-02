<?php

namespace App\Controller;

use App\Entity\ContactPartenaire;
use App\Form\ContactPartenaireType;
use App\Repository\ContactPartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact/partenaire")
 */
class ContactPartenaireController extends Controller
{
    /**
     * @Route("/", name="contact_partenaire_index", methods="GET")
     */
    public function index(ContactPartenaireRepository $contactPartenaireRepository): Response
    {
        return $this->render('contact_partenaire/index.html.twig', ['contact_partenaires' => $contactPartenaireRepository->findAll()]);
    }

    /**
     * @Route("/new", name="contact_partenaire_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $contactPartenaire = new ContactPartenaire();
        $form = $this->createForm(ContactPartenaireType::class, $contactPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactPartenaire);
            $em->flush();

            return $this->redirectToRoute('contact_partenaire_index');
        }

        return $this->render('contact_partenaire/new.html.twig', [
            'contact_partenaire' => $contactPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_partenaire_show", methods="GET")
     */
    public function show(ContactPartenaire $contactPartenaire): Response
    {
        return $this->render('contact_partenaire/show.html.twig', ['contact_partenaire' => $contactPartenaire]);
    }

    /**
     * @Route("/{id}/edit", name="contact_partenaire_edit", methods="GET|POST")
     */
    public function edit(Request $request, ContactPartenaire $contactPartenaire): Response
    {
        $form = $this->createForm(ContactPartenaireType::class, $contactPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_partenaire_edit', ['id' => $contactPartenaire->getId()]);
        }

        return $this->render('contact_partenaire/edit.html.twig', [
            'contact_partenaire' => $contactPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_partenaire_delete", methods="DELETE")
     */
    public function delete(Request $request, ContactPartenaire $contactPartenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactPartenaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contactPartenaire);
            $em->flush();
        }

        return $this->redirectToRoute('contact_partenaire_index');
    }
}

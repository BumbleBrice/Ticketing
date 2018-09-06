<?php

namespace App\Controller;

use App\Entity\ContactPublic;
use App\Form\ContactPublicType;
use App\Repository\ContactPublicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/contact/public")
 */
class ContactPublicController extends Controller
{
    /**
     * @Route("/", name="contact_public_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
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

            $this->addFlash(
                'notice',
                'votre message a bien été enregistrer'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('contact_public/new.html.twig', [
            'contact_public' => $contactPublic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_public_show", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function show(ContactPublic $contactPublic): Response
    {
        $contactPublic->setStatus('vue');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($contactPublic);
        $em->flush();

        return $this->render('contact_public/show.html.twig', ['contact_public' => $contactPublic]);
    }

    /**
     * @Route("/{id}", name="contact_public_delete", methods="DELETE")
     * @Security("is_granted('ROLE_ADMIN')")
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

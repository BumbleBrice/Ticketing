<?php

namespace App\Controller;

use App\Entity\ContactPro;
use App\Form\ContactProType;
use App\Repository\ContactProRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/contact/pro")
 */
class ContactProController extends Controller
{
    /**
     * @Route("/", name="contact_pro_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(ContactProRepository $contactProRepository): Response
    {
        return $this->render('contact_pro/index.html.twig', ['contact_pros' => $contactProRepository->findAll()]);
    }

    /**
     * @Route("/new", name="contact_pro_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $contactPro = new ContactPro();
        $form = $this->createForm(ContactProType::class, $contactPro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactPro);
            $em->flush();

            $this->addFlash(
                'notice',
                'votre message a bien été Envoyer'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('contact_pro/new.html.twig', [
            'contact_pro' => $contactPro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_pro_show", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function show(ContactPro $contactPro): Response
    {
        $contactPro->setStatus('vue');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($contactPro);
        $em->flush();

        return $this->render('contact_pro/show.html.twig', ['contact_pro' => $contactPro]);
    }

    /**
     * @Route("/{id}", name="contact_pro_delete", methods="DELETE")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, ContactPro $contactPro): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactPro->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contactPro);
            $em->flush();
        }

        return $this->redirectToRoute('contact_pro_index');
    }
}

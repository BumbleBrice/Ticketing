<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\ContactPublic;
use App\Form\ContactPublicType;
use App\Repository\ContactPublicRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        return $this->render('contact_public/index.html.twig', ['contact_publics' => $contactPublicRepository->findAllR()]);
    }

    /**
     * @Route("/new", name="contact_public_new", methods="GET|POST")
     */
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $contactPublic = new ContactPublic();
        $form = $this->createForm(ContactPublicType::class, $contactPublic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form['newsletter']->getData())
            {
                $em = $this->getDoctrine()->getManager();

                $newsletter = new Newsletter();
                $newsletter->setEmail($form['email']->getData());
                $newsletter->setFirstname($form['prenom']->getData());
                $newsletter->setLastname($form['nom']->getData());
                $em->persist($newsletter);
                $em->flush();
            }

            // TOODOO envoyer l'email de préveunage
            $message = (new \Swift_Message('Nouveaux contact Public'))
                ->setFrom('testeur@tiste.com')
                ->setTo('meunier_33@live.fr')
                ->setBody(
                    $this->renderView(
                        'emails/contacts.html.twig',
                        [
                            'type' => 'Public',
                            'nom' => $form['nom']->getData(),
                            'prenom' => $form['prenom']->getDAta()
                        ]
                    ),
                    'text/html'
                )
                /*
                * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        [
                            'name' => $name
                        ]
                    ),
                    'text/plain'
                )
                */
            ;

            $mailer->send($message);

            $em = $this->getDoctrine()->getManager();
            $contactPublic->setDate(new \DateTime('NOW'));
            $em->persist($contactPublic);
            $em->flush();

            $this->addFlash(
                'notice',
                'votre message a bien été Envoyer'
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

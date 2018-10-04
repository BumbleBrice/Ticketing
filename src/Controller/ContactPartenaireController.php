<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\ContactPartenaire;
use App\Form\ContactPartenaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ContactPartenaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/contact/partenaire")
 */
class ContactPartenaireController extends Controller
{
    /**
     * @Route("/", name="contact_partenaire_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(ContactPartenaireRepository $contactPartenaireRepository): Response
    {
        return $this->render('contact_partenaire/index.html.twig', ['contact_partenaires' => $contactPartenaireRepository->findAllR()]);
    }

    /**
     * @Route("/new", name="contact_partenaire_new", methods="GET|POST")
     */
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $contactPartenaire = new ContactPartenaire();
        $form = $this->createForm(ContactPartenaireType::class, $contactPartenaire);
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
            $message = (new \Swift_Message('Nouveaux contact partenaire'))
                ->setFrom('testeur@tiste.com')
                ->setTo('meunier_33@live.fr')
                ->setBody(
                    $this->renderView(
                        'emails/contacts.html.twig',
                        [
                            'type' => 'Partenaire',
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
            $contactPartenaire->setDate(new \DateTime('NOW'));
            $em->persist($contactPartenaire);
            $em->flush();

            $this->addFlash(
                'notice',
                'votre message a bien été envoyer'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('contact_partenaire/new.html.twig', [
            'contact_partenaire' => $contactPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_partenaire_show", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function show(ContactPartenaire $contactPartenaire): Response
    {
        $contactPartenaire->setStatus('vue');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($contactPartenaire);
        $em->flush();

        return $this->render('contact_partenaire/show.html.twig', ['contact_partenaire' => $contactPartenaire]);
    }

    /**
     * @Route("/{id}", name="contact_partenaire_delete", methods="DELETE")
     * @Security("is_granted('ROLE_ADMIN')")
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

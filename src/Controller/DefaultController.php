<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Spectacle;
use App\Service\Weezevent;
use App\Form\ChangePasswordType;
use App\Repository\SpectacleRepository;
use App\Repository\ContactProRepository;
use App\Repository\ContactPublicRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\ContactPartenaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("/gestion", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function admin(Request $request, UserPasswordEncoderInterface $encoder, ContactPartenaireRepository $ContactPartenaireRepository, ContactPublicRepository $ContactPublicRepository, ContactProRepository $ContactProRepository)
    {
        $contactPro = $ContactProRepository->findBy(['status' => 'vue']);
        $contactPublic = $ContactPublicRepository->findBy(['status' => 'vue']);
        $contactPartenaire = $ContactPartenaireRepository->findBy(['status' => 'vue']);

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setPassword($encoder->encodePassword($user, $user->NewPassword));

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('admin/index.html.twig', [
            'vues' => count($contactPro) + count($contactPublic) + count($contactPartenaire),
            'form' => $form->createView()
        ]);
    }
}

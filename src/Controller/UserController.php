<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, AuthorizationCheckerInterface $authChecker, UserPasswordEncoderInterface $encoder): Response
    {
        if (false === $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setPassword($encoder->encodePassword($user, $user->NewPassword));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}

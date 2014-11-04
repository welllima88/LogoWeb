<?php

namespace Cib\Bundle\UserBundle\Controller;

use Cib\Bundle\UserBundle\Form\UserType;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

    /**
     * @param Request $request
     *
     * @return array
     * @Route("/loggedin/admin/display/users/{page}", name="displayUsers", defaults={"page" = 1})
     * @Template()
     */
    public function displayUsersAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('CibUserBundle:User');
        $users = $userRepo->findAll();

        $csrf = $this->get('form.csrf_provider');
        foreach($users as $user)
            $user->setToken($csrf->generateCsrfToken($user->getId()));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $page,
            10
        );

//        foreach($users as $user)
//            var_dump($user->getRoles());
//        die;
//        var_dump($users->getRoles());die;
        return[
            'pagination' => $pagination,
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     * @Route("/loggedin/edit/user/{id}/{token}", name="editUser", defaults={"token" = ""})
     * @Template()
     */
    public function editUserAction(Request $request, $id, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $repoUser = $em->getRepository('CibUserBundle:User');
        $user = $repoUser->find($id);
//        var_dump($user);die;
        $csrf = $this->get('form.csrf_provider');
        if($token == $csrf->generateCsrfToken($user->getId()) || $request->isMethod('post'))
        {
            $form = $this->createForm(new UserType($this->getRoles()),$user);
            $form->handleRequest($request);
            if($form->isValid())
            {
//                var_dump($form->getData());
                $user = $form->getData();
//                var_dump($user);
                $em->persist($user);
                $em->flush();
            }

        }
        else
            throw $this->createNotFoundException("Page introuvable");


        return[
            'form' => $form->createView(),
            'id' => $user->getId(),
        ];
    }

    protected function getRoles() {
        $roles = array();

        foreach ($this->container->getParameter('security.role_hierarchy.roles') as $name => $rolesHierarchy) {
            $roles[$name] = $name;

            foreach ($rolesHierarchy as $role) {
                if (!isset($roles[$role])) {
                    $roles[$role] = $role;
                }
            }
        }

        return $roles;
    }
}

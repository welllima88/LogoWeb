<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 15:03
 */

namespace Cib\Bundle\CoreBundle\Controller;


use Cib\Bundle\CoreBundle\Entity\Parameters;
use Cib\Bundle\CoreBundle\Form\ParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{


    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'toto');
    }

    /**
     * @param Request $request
     *
     * @return array
     * @Route("/loggedin/display/parameters", name="displayParameters")
     *
     * @Template()
     */
    public function displayParametersAction(Request $request)
    {
        $em = $this->getDoctrine();
        $repo = $em->getRepository('CibCoreBundle:Parameters');
        $paramaters = $repo->findAll();

        if(!$paramaters)
            $parameters = new Parameters();

        return[
            'parameters' => $paramaters,
        ];
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/add/parameters", name="addParameters")
     *
     * @Template()
     */
    public function addParametersAction(Request $request)
    {
        $parameters = new Parameters();
        $form = $this->createForm(new ParametersType(),$parameters);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid())
            {
                $param = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($param);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Ajout effectuée');
                return $this->redirect($this->generateUrl('displayParameters'));
            }
        }

        return[
            'form' => $form->createView(),
        ];

    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/edit/parameters/{id}", name="editParameters", defaults={"id" = 0})
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Template()
     */
    public function editParametersAction(Request $request,$id)
    {
        if($id == 0)
            throw $this->createNotFoundException('Page introuvable');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCoreBundle:Parameters');
        $parameters = $repo->find($id);

        $form = $this->createForm(new ParametersType(),$parameters);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $parameters = $form->getData();
            $em->persist($parameters);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Modification effectuée');
            return $this->redirect($this->generateUrl('displayParameters'));
        }

        return[
            'form' => $form->createView(),
            'param' => $parameters,
        ];
    }
} 
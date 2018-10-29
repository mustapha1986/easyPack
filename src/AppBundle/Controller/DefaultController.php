<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Exp;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserShowType;
use AppBundle\Form\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{

    /**
     * @Route("/form", name="formCarrier")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFormAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $type = $request->request->get('type');
        $client = $request->request->get('client');

        $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['client' => $client, 'type' => $type]);


        $session = $request->getSession();
        $session->set('carrier', null);
        $session->set('client', null);

        if ($type !== null) {
            $session->set('carrier', $type);
        }

        if ($client !== null) {
            $session->set('client', $client);
        }
        if ($request->isXmlHttpRequest()) {

            if ($clientHasCarrier !== null) {
                $form = $this->createForm(UserType::class, $clientHasCarrier,
                    ['transporteur' => $clientHasCarrier->getType()]);
            } else {
                $clientHasCarrier = new User();
                $form = $this->createForm(UserType::class, $clientHasCarrier,
                    ['transporteur' => $type]);
            }

            // replace this example code with whatever you need
            return $this->render('default/form.html.twig', [
                'form' => $form->createView()
            ]);

        }


    }

    /**
     * @Route("/add", name="newConfig")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $carriers = $this->getParameter('carriers');


        if ($request->request->get('carrier') !== null) {
            $type = $session->get('carrier');
        } else {
            $type = null;
        }


        if ($session->get('client') !== null) {
            $client = $session->get('client');
        }else{
            $client = null;
        }

        $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['client' => $client, 'type' => $type]);

        //die;
        if ($clientHasCarrier !== null) {
            $form = $this->createForm(UserType::class, $clientHasCarrier,
                ['transporteur' => $type]);
        } else {
            $clientHasCarrier = new User();
            $form = $this->createForm(UserType::class, $clientHasCarrier,
                ['transporteur' => $type]);
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if ($type !== null) {
                $clientHasCarrier->setType($type);
            }
            $em->persist($clientHasCarrier);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'L\'opération a été effectuée');

            return $this->redirectToRoute('list_client_has_carrier');

        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'carriers' => $carriers
        ]);
    }


    /**
     * @Route("/list", name="list_client_has_carrier")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $clientHasCarrierList = $em->getRepository('AppBundle:User')->findAll();
        if ($clientHasCarrierList === null) {
            throw new NotFoundHttpException('Enregistrement non trouvé');
        }

        return $this->render('default/list.html.twig', [
            'clientHasCarrierList' => $clientHasCarrierList
        ]);


    }


    /**
     * @Route("/accueil", name="accueil")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction()
    {

        return $this->render('layout.html.twig');
    }


    /**
     * @Route("/detail/{id}", name="detail_config")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $id)
    {

        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

            if ($clientHasCarrier === null) {
                throw new NotFoundHttpException('404 not found');
            }
            $form = $this->createForm(UserEditType::class, $clientHasCarrier, ['transporteur' =>
                $clientHasCarrier->getType()]);

            return $this->render('default/modalTransporteurs/'.$clientHasCarrier->getType().'.html.twig', ['form' => $form->createView(), 'elem' =>
                $clientHasCarrier]);
        }


    }








    /**
     * @Route("/edit/{id}", name="edit_config")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {


            $em = $this->getDoctrine()->getManager();
            $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

            if ($clientHasCarrier === null) {
                throw new NotFoundHttpException('404 not found');
            }
            $form = $this->createForm(UserType::class, $clientHasCarrier, ['transporteur' =>
                $clientHasCarrier->getType()]);


            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                $em->persist($clientHasCarrier);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'L\'opération a été effectuée');

                return $this->redirectToRoute('list_client_has_carrier');

            }

        return $this->render('default/modalTransporteurs/'.$clientHasCarrier->getType().'.html.twig', ['form' => $form->createView(), 'elem' =>
            $clientHasCarrier]);

    }




    /**
     * @Route("/deleteModal/{id}", name="delete_modal_config")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteModalAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);


        return $this->render('default/modalTransporteurs/modalDelete.html.twig',
            [ 'elem' => $clientHasCarrier]);
    }


    /**
     * @Route("/delete/{id}", name="delete_config")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $clientHasCarrier = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

        $em->remove($clientHasCarrier);
        $em->flush();

        $response = new JsonResponse(array('id' => $id));
        return $response;
    }
}

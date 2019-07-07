<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RefPlace;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Refplace controller.
 *
 * @Route("refplace")
 */
class RefPlaceController extends Controller
{
    /**
     * Lists all refPlace entities.
     *
     * @Route("/", name="refplace_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $refPlaces = $em->getRepository('AppBundle:RefPlace')->findAll();

        return $this->render('refplace/index.html.twig', array(
            'refPlaces' => $refPlaces,
        ));
    }

    /**
     * Creates a new refPlace entity.
     *
     * @Route("/new", name="refplace_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $refPlace = new Refplace();
        $form = $this->createForm('AppBundle\Form\RefPlaceType', $refPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($refPlace);
            $em->flush();

            return $this->redirectToRoute('refplace_show', array('id' => $refPlace->getId()));
        }

        return $this->render('refplace/new.html.twig', array(
            'refPlace' => $refPlace,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a refPlace entity.
     *
     * @Route("/{id}", name="refplace_show")
     * @Method("GET")
     */
    public function showAction(RefPlace $refPlace)
    {
        $deleteForm = $this->createDeleteForm($refPlace);

        return $this->render('refplace/show.html.twig', array(
            'refPlace' => $refPlace,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing refPlace entity.
     *
     * @Route("/{id}/edit", name="refplace_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RefPlace $refPlace)
    {
        $deleteForm = $this->createDeleteForm($refPlace);
        $editForm = $this->createForm('AppBundle\Form\RefPlaceType', $refPlace);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('refplace_edit', array('id' => $refPlace->getId()));
        }

        return $this->render('refplace/edit.html.twig', array(
            'refPlace' => $refPlace,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a refPlace entity.
     *
     * @Route("/{id}", name="refplace_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RefPlace $refPlace)
    {
        $form = $this->createDeleteForm($refPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($refPlace);
            $em->flush();
        }

        return $this->redirectToRoute('refplace_index');
    }

    /**
     * Creates a form to delete a refPlace entity.
     *
     * @param RefPlace $refPlace The refPlace entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RefPlace $refPlace)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('refplace_delete', array('id' => $refPlace->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

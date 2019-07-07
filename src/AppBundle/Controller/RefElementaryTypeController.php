<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RefElementaryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Refelementarytype controller.
 *
 * @Route("refelementarytype")
 */
class RefElementaryTypeController extends Controller
{
    /**
     * Lists all refElementaryType entities.
     *
     * @Route("/", name="refelementarytype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $refElementaryTypes = $em->getRepository('AppBundle:RefElementaryType')->findAll();

        return $this->render('refelementarytype/index.html.twig', array(
            'refElementaryTypes' => $refElementaryTypes,
        ));
    }

    /**
     * Creates a new refElementaryType entity.
     *
     * @Route("/new", name="refelementarytype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $refElementaryType = new Refelementarytype();
        $form = $this->createForm('AppBundle\Form\RefElementaryTypeType', $refElementaryType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($refElementaryType);
            $em->flush();

            return $this->redirectToRoute('refelementarytype_show', array('id' => $refElementaryType->getId()));
        }

        return $this->render('refelementarytype/new.html.twig', array(
            'refElementaryType' => $refElementaryType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a refElementaryType entity.
     *
     * @Route("/{id}", name="refelementarytype_show")
     * @Method("GET")
     */
    public function showAction(RefElementaryType $refElementaryType)
    {
        $deleteForm = $this->createDeleteForm($refElementaryType);

        return $this->render('refelementarytype/show.html.twig', array(
            'refElementaryType' => $refElementaryType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing refElementaryType entity.
     *
     * @Route("/{id}/edit", name="refelementarytype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RefElementaryType $refElementaryType)
    {
        $deleteForm = $this->createDeleteForm($refElementaryType);
        $editForm = $this->createForm('AppBundle\Form\RefElementaryTypeType', $refElementaryType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('refelementarytype_edit', array('id' => $refElementaryType->getId()));
        }

        return $this->render('refelementarytype/edit.html.twig', array(
            'refElementaryType' => $refElementaryType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a refElementaryType entity.
     *
     * @Route("/{id}", name="refelementarytype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RefElementaryType $refElementaryType)
    {
        $form = $this->createDeleteForm($refElementaryType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($refElementaryType);
            $em->flush();
        }

        return $this->redirectToRoute('refelementarytype_index');
    }

    /**
     * Creates a form to delete a refElementaryType entity.
     *
     * @param RefElementaryType $refElementaryType The refElementaryType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RefElementaryType $refElementaryType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('refelementarytype_delete', array('id' => $refElementaryType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

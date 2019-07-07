<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pokemon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Pokemon controller.
 *
 * @Route("pokemon")
 */
class PokemonController extends Controller
{
    /**
     * Lists all pokemon entities.
     *
     * @Route("/", name="pokemon_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pokemons = $em->getRepository('AppBundle:Pokemon')->findAll();

        return $this->render('pokemon/index.html.twig', array(
            'pokemons' => $pokemons,
        ));
    }

    /**
     * Creates a new pokemon entity.
     *
     * @Route("/new", name="pokemon_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pokemon = new Pokemon();
        $form = $this->createForm('AppBundle\Form\PokemonType', $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pokemon);
            $em->flush();

            return $this->redirectToRoute('pokemon_show', array('id' => $pokemon->getId()));
        }

        return $this->render('pokemon/new.html.twig', array(
            'pokemon' => $pokemon,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pokemon entity.
     *
     * @Route("/{id}", name="pokemon_show")
     * @Method("GET")
     */
    public function showAction(Pokemon $pokemon)
    {
        $deleteForm = $this->createDeleteForm($pokemon);

        return $this->render('pokemon/show.html.twig', array(
            'pokemon' => $pokemon,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pokemon entity.
     *
     * @Route("/{id}/edit", name="pokemon_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pokemon $pokemon)
    {
        $deleteForm = $this->createDeleteForm($pokemon);
        $editForm = $this->createForm('AppBundle\Form\PokemonType', $pokemon);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pokemon_edit', array('id' => $pokemon->getId()));
        }

        return $this->render('pokemon/edit.html.twig', array(
            'pokemon' => $pokemon,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pokemon entity.
     *
     * @Route("/{id}", name="pokemon_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pokemon $pokemon)
    {
        $form = $this->createDeleteForm($pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pokemon);
            $em->flush();
        }

        return $this->redirectToRoute('pokemon_index');
    }

    /**
     * Creates a form to delete a pokemon entity.
     *
     * @param Pokemon $pokemon The pokemon entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pokemon $pokemon)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pokemon_delete', array('id' => $pokemon->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RefElementaryType;
use AppBundle\Entity\RefPokemon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Refpokemon controller.
 *
 * @Route("refpokemon")
 */
class RefPokemonController extends Controller
{
    /**
     * Lists all refPokemon entities.
     *
     * @Route("/", name="refpokemon_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $pokemons = $this->getDoctrine()->getRepository('AppBundle:RefPokemon')->findAll();
        $context = array();
        foreach($pokemons as $pkm) {
            $data = array(
                'id' => $pkm->getId(),
                'nom' => $pkm->getNom()
            );
            $type = ucfirst($pkm->getType1()->getLibelle());
            try {
                $data['type'] = $type . ' - ' . ucfirst($pkm->getType2()->getLibelle());
            } catch (\Exception $e) {
                if ($e instanceof EntityNotFoundException) { // type2 does not exist
                    $data['type'] = $type;
                }
            }
            array_push($context, $data);
        }
        $nbPokemons = count($context);
        $nbBases =  $this->getDoctrine()->getRepository('AppBundle:RefPokemon')->findByEvolution(false);
        $session = $request->getSession();
        $status = $session->get("status", null);
        $context = array(
            'pokemons' => $context,
            'nbPokemons' => $nbPokemons,
            'nbBases' => count($nbBases),
            'nbEvos' => $nbPokemons - count($nbBases)
        );
        if (isset($status)) {
            if ($status == "delete") {
                $context['status'] = "is-warning";
                $context['message'] = "Le Pokemon a bien été supprimé !";
            }
            $status = $session->remove("status");
        }
        return $this->render('refpokemon/index.html.twig', $context);
    }

    /**
     * Creates a new refPokemon entity.
     *
     * @Route("/new", name="refpokemon_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $refPokemon = new RefPokemon();
        $form = $this->createForm('AppBundle\Form\RefPokemonType', $refPokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $refPokemon->setEvolution(($request->request->get("isEvo") == '0') ? false : true);
            $refPokemon->setStarter(($request->request->get("isStarter") == '0') ? false : true);
            $dump = new RefElementaryType();
            $dump->setId(0);
            $dump->setLibelle('Aucun');
            $em = $this->getDoctrine()->getManager();
            $em->persist($dump);
            $refPokemon->setType2(($refPokemon->getType2() !== null) ? $refPokemon->getType2() : $dump);
            $em->persist($refPokemon);
            $em->flush();
            $em->remove($dump);
            $em->flush();
            return $this->redirectToRoute('refpokemon_show', array('id' => $refPokemon->getId()));
        }

        return $this->render('refpokemon/new.html.twig', array(
            'refPokemon' => $refPokemon,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/stats", name="refpokemon_stats")
     * @Method("GET")
     */
    public function statsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:RefPokemon')->findStats();
        return $this->render('refpokemon/stats.html.twig', array(
            'types' => $types
        ));
    }

    /**
     * Finds and displays a refPokemon entity.
     *
     * @Route("/{id}", name="refpokemon_show")
     * @Method("GET")
     */
    public function showAction(RefPokemon $refPokemon)
    {
        $res = array(
            'id' => $refPokemon->getId(),
            'nom' => $refPokemon->getNom(),
            'evolution' => $refPokemon->getEvolution()
        );
        $type = ucfirst($refPokemon->getType1()->getLibelle());
        try {
            $res['type'] = $type . ' - ' . ucfirst($refPokemon->getType2()->getLibelle());
        } catch (\Exception $e) {
            if ($e instanceof EntityNotFoundException) { // type2 does not exist
                $res['type'] = $type;
            }
        }
        return $this->render('refpokemon/show.html.twig', array(
            'refPokemon' => $res
        ));
    }

    /**
     * Displays a form to edit an existing refPokemon entity.
     *
     * @Route("/{id}/edit", name="refpokemon_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RefPokemon $refPokemon)
    {
        $deleteForm = $this->createDeleteForm($refPokemon);
        $editForm = $this->createForm('AppBundle\Form\RefPokemonType', $refPokemon);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('refpokemon_edit', array('id' => $refPokemon->getId()));
        }

        return $this->render('refpokemon/edit.html.twig', array(
            'refPokemon' => $refPokemon,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a refPokemon entity.
     *
     * @Route("/{id}/delete", name="refpokemon_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, RefPokemon $refPokemon)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($refPokemon);
        $em->flush();
        $session = $request->getSession();
        $session->set('status', 'delete');
        return $this->redirectToRoute('refpokemon_index');
    }

    /**
     * Creates a form to delete a refPokemon entity.
     *
     * @param RefPokemon $refPokemon The refPokemon entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RefPokemon $refPokemon)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('refpokemon_delete', array('id' => $refPokemon->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

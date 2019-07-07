<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pokemon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;
use AppBundle\Service\Utils;

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
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if (!$isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $em = $this->getDoctrine()->getManager();
            $trainer = $session->get("trainer", null);
            $pokemons = $em->getRepository('AppBundle:Pokemon')->findByDresseur($trainer);
            $status = $session->get("status", null);
            $context = array('pokemons' => $pokemons);
            if (isset($status)) {
                switch ($status) {
                    case 'training':
                        $context['status'] = "is-success";
                        $context['message'] = "Votre Pokemon commence son entraînement !";
                        break;
                    case 'sold':
                        $context['status'] = "is-success";
                        $context['message'] = "Votre Pokemon est en vente !";
                        break;
                    case 'catch':
                        $context['status'] = "is-success";
                        $context['message'] = "Vous avez capturé un nouveau Pokemon !";
                        break;
                    default:
                        break;
                }
                
                $status = $session->remove("status");
            }
            return $this->render('pokemon/index.html.twig', $context);
        }
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
    public function showAction(Request $request, Pokemon $pokemon)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if (!$isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $res = array(
                'id' => $pokemon->getId(),
                'nom' => $pokemon->getRefPokemon()->getNom(),
                'sexe' => $pokemon->getSexe(),
                'do' => $pokemon->getDresseur()->getUsername(),
                'niveau' => $pokemon->getNiveau(),
                'xp' => $pokemon->getXp(),
                'training' => $pokemon->getDateDernierEntrainement(),
                'aVendre' => $pokemon->getAVendre(),
                'prix' => $pokemon->getPrix()
            );
            $type = ucfirst($pokemon->getRefPokemon()->getType1()->getLibelle());
            try {
                $res['type'] = $type . ' - ' . ucfirst($pokemon->getRefPokemon()->getType2()->getLibelle());
            } catch (\Exception $e) {
                if ($e instanceof EntityNotFoundException) { // type2 does not exist
                    $res['type'] = $type;
                }
            }
            return $this->render('pokemon/show.html.twig', array(
                'pokemon' => $res,
                'places' => $this->getDoctrine()->getManager()->getRepository('AppBundle:RefPlace')->findAll()
            ));
        }
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
     * @Route("/{id}/train", name="pokemon_train")
     * @Method("POST")
     */
    public function trainAction(Request $request, Pokemon $pokemon, Utils $utils)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if (!$isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $pokemon->setDateDernierEntrainement(time());
            $pokemon->setXp($pokemon->getXp() + rand(10, 30));
            $pokemon->setNiveau($utils->getNiveau($pokemon->getXp(), $pokemon->getRefPokemon()->getTypeCourbeNiveau()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($pokemon);
            $em->flush();
            $session->set('status', 'training');
            return $this->redirectToRoute('pokemon_index');
        }
    }

    /**
     * @Route("/{id}/catch", name="pokemon_catch")
     * @Method("POST")
     */
    public function catchAction(Request $request, Pokemon $pokemon, Utils $utils)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if (!$isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $pokemon->setDateDernierEntrainement(time());
            $em = $this->getDoctrine()->getManager();
            $em->persist($pokemon);
            $nvx = rand(0, 5);
            $moreOrLess = rand(0, 1);
            $nvx = ($moreOrLess == 0) ? $pokemon->getNiveau() + $nvx : $pokemon->getNiveau() - $nvx;
            $pkm = $em->getRepository('AppBundle:RefPokemon')->findRandomByPlace($request->request->get("place"));
            $trainer = $session->get("trainer");
            $trainer = $em->getRepository('AppBundle:Trainer')->findOneById($trainer->getId());
            $new = new Pokemon();
            $new->setAVendre(false);
            $new->setDateDernierEntrainement(0);
            $new->setDresseur($trainer);
            $new->setNiveau($nvx);
            $new->setPrix(0);
            $new->setRefPokemon($pkm);
            $new->setSexe((rand(0, 1) == 0) ? 'M' : 'F');
            $new->setXp($utils->getXp($pokemon->getNiveau(), $pokemon->getRefPokemon()->getTypeCourbeNiveau()));
            $em->persist($new);
            $em->flush();
            $session->set('status', 'catch');
            return $this->redirectToRoute('pokemon_index');
        }
    }
    
    /**
     * @Route("/{id}/sell", name="pokemon_sell")
     * @Method("POST")
     */
    public function sellAction(Request $request, Pokemon $pokemon)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if (!$isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $pokemon->setAVendre(true);
            $price = $request->request->get("price");
            $pokemon->setPrix($price);
            $em = $this->getDoctrine()->getManager();
            $em->persist($pokemon);
            $em->flush();
            $session->set('status', 'sold');
            return $this->redirectToRoute('pokemon_index');
        }
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

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pokemon;
use AppBundle\Entity\Trainer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Utils;

/**
 * Trainer controller.
 *
 * @Route("trainer")
 */
class TrainerController extends Controller
{
    /**
     * Lists all trainer entities.
     *
     * @Route("/", name="trainer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $trainers = $em->getRepository('AppBundle:Trainer')->findAll();

        return $this->render('trainer/index.html.twig', array(
            'trainers' => $trainers,
        ));
    }

    /**
     * Creates a new trainer entity.
     *
     * @Route("/new", name="trainer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $trainer = new Trainer();
        $form = $this->createForm('AppBundle\Form\TrainerType', $trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trainer);
            $em->flush();

            return $this->redirectToRoute('trainer_show', array('id' => $trainer->getId()));
        }

        return $this->render('trainer/new.html.twig', array(
            'trainer' => $trainer,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if ($isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $context = array();
            if ($request->isMethod("POST")) {
                $email = $request->request->get("email");
                $password = $request->request->get("password");
                if (!empty($email) && !empty($password)) {
                    $em = $this->getDoctrine()->getManager();
                    $trainer = $em->getRepository("AppBundle:Trainer")->findOneByEmail($email);
                    if (isset($trainer)) {
                        if (password_verify($password, $trainer->getPassword())) {
                            $session->set("isConnected", true);
                            $session->set("trainer", $trainer);
                            $isConnected = $session->get("isConnected");
                            return $this->redirectToRoute("homepage");
                        }
                    }
                    $context['status'] = "is-danger";
                    $context['message'] = "Email ou mot de passe incorrect...";
                } else {
                    $context['status'] = "is-warning";
                    $context['message'] = "Veuillez saisir tous les chamsp...";
                }
            }
            return $this->render("trainer/index.html.twig", $context);
        }
    }

    /**
     * @Route("/inscription", name="inscription")
     * @Method({"GET", "POST"})
     */
    public function inscriptionAction(Request $request, Utils $utils)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        $trainer = new Trainer();
        if ($isConnected) {
            return $this->redirectToRoute("homepage");
        } else {
            $em = $this->getDoctrine()->getManager();
            $starters = $em->getRepository("AppBundle:RefPokemon")->findByStarter(true);
            $context = array(
                "starters" => $starters
            );
            if ($request->isMethod("POST")) {
                $username = $request->request->get("username");
                $password = $request->request->get("password");
                $email = $request->request->get("email");
                $starter = $request->request->get("starter");
                if (!empty($username) && !empty($password)) {
                    $trainer = $em->getRepository("AppBundle:Trainer")->findOneByEmail($email);
                    if (!isset($trainer)) {
                        $trainer = new Trainer();
                        $trainer->setUsername($username);
                        $trainer->setPassword(password_hash($password, PASSWORD_BCRYPT));
                        $trainer->setEmail($email);
                        $trainer->setIsActive(true);
                        $trainer->setNbPieces(5000);
                        $trainer->setStarterId($starter);
                        $em->persist($trainer);
                        $em->flush();
                        $pokemon = new Pokemon();
                        $pokemon->setAVendre(false);
                        $pokemon->setDateDernierEntrainement(0);
                        $pokemon->setDresseur($trainer);
                        $pokemon->setNiveau(5);
                        $pokemon->setPrix(0);
                        $pokemon->setRefPokemon($em->getRepository("AppBundle:RefPokemon")->findOneById($starter));
                        $pokemon->setSexe((rand(0, 1) == 0) ? 'M' : 'F');
                        $pokemon->setXp($utils->getXp($pokemon->getNiveau(), $pokemon->getRefPokemon()->getTypeCourbeNiveau()));
                        $em->persist($pokemon);
                        $em->flush();
                        $context['status'] = "is-success";
                        $context['message'] = "Le dresseur a bien été enregistré dans le jeu";
                    } else {
                        $context['status'] = "is-danger";
                        $context['message'] = "Le dresseur est déjà enregistré dans le jeu";
                    }
                } else {
                    $context['status'] = "is-danger";
                    $context['message'] = "Veuillez saisir tous les champs";
                }
            }
            return $this->render("trainer/new.html.twig", $context);
        }
    }

    /**
     * @Route("/disconnect", name="disconnect")
     * @Method("GET")
     */
    public function disconnectAction(Request $request)
    {
        $request->getSession()->invalidate();
        return $this->redirectToRoute("homepage");
    }

    /**
     * Finds and displays a trainer entity.
     *
     * @Route("/{id}", name="trainer_show")
     * @Method("GET")
     */
    public function showAction(Trainer $trainer)
    {
        $deleteForm = $this->createDeleteForm($trainer);

        return $this->render('trainer/show.html.twig', array(
            'trainer' => $trainer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing trainer entity.
     *
     * @Route("/{id}/edit", name="trainer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trainer $trainer)
    {
        $deleteForm = $this->createDeleteForm($trainer);
        $editForm = $this->createForm('AppBundle\Form\TrainerType', $trainer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trainer_edit', array('id' => $trainer->getId()));
        }

        return $this->render('trainer/edit.html.twig', array(
            'trainer' => $trainer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trainer entity.
     *
     * @Route("/{id}", name="trainer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trainer $trainer)
    {
        $form = $this->createDeleteForm($trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trainer);
            $em->flush();
        }

        return $this->redirectToRoute('trainer_index');
    }

    /**
     * Creates a form to delete a trainer entity.
     *
     * @param Trainer $trainer The trainer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trainer $trainer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trainer_delete', array('id' => $trainer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

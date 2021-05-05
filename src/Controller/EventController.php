<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="event")
     */
    public function index(): Response //afficher l'ensembles des evenements
    {

        $repo = $this->getDoctrine()->getRepository(Evenement::class);
        $evenements = $repo->findAll();
        // dd($evenements);
        return $this->render('event/index.html.twig', [
            "evenements" => $evenements
        ]);
    }

    /**
     * 
     * @Route("/affichage", name="affichage")
     */
    public function affichage()
    {
        return $this->render('affichage/affiche.html.twig');
    }

    /**
     * @Route("/event/afficheOne/{id}", name="afficheOne")
     */
    public function afficheOne($id) //afficher par unique 
    {
        $repo = $this->getDoctrine()->getRepository(Evenement::class);
        $evenment = $repo->find($id);
        // dd($evenment);
        return $this->render("event/show.html.twig", [
            "evenement" => $evenment
        ]);
    }

    /**
     *@Route("/event/editEvent/{id}", name="editEvent")
     */
    public function editEvent(Evenement $event = null, Request $requst, ObjectManager $manager)
    {
        if (!$event) {
            $event = new Evenement();
        }
        $form = $this->createForm(EvenementType::class, $event);
        // dd($form);
        $form->handleRequest($requst);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute("event");
        }
        return $this->render("event/edit.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }
    /**
     *@Route("/event/supprime/{id}", name="deleteEvent") 
     */
    public function supprimeEvent(Evenement $event)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($event);
        $manager->flush();
        return $this->redirectToRoute("event");
    }


    /**
     * @Route("/event/add",name="addEvent")
     */
    public function addEvent(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->setUser($user);
            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute("event");
        }
        return $this->render("event/add.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }
}

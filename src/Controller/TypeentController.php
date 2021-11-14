<?php

namespace App\Controller;

use App\Entity\Typeent;
use App\Form\AddTypeEntType;
use App\Form\EditTEType;
use App\Repository\TypeentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeentController extends AbstractController
{
    /**
     * @Route("/typeent", name="typeent")
     */
    public function index(TypeentRepository $repository): Response
    {
        $em=$repository->findAll();
        return $this->render('typeent/index.html.twig', [ 'typeent'=>$em]);
    }
    /**
     * @Route("/typeentretien", name="typeentretien")
     */
    public function index2(TypeentRepository $repository): Response
    {
        $em=$repository->findAll();
        return $this->render('typeent/index2.html.twig', [ 'typeent'=>$em]);
    }

    /**
     * @Route ("/addTypeTe", name="addTypeTe")
     */
    public function addQuest (Request $request)
    {
        $ent= new Typeent();
        $form = $this->createForm(AddTypeEntType::class, $ent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ent);
            $em->flush();
            return $this->redirect("/typeent");

        }
        return $this->render("typeent/Addte.html.twig",['form'=>$form->createView()]);
    }

    /**
     * @Route ("editTypeTe/{id}", name="editTypeTe")
     */
    public function editQuest(Typeent $typeent, Request $request, EntityManagerInterface $em){

        $form=$this->createForm(EditTEType::class,$typeent);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $typeent=$form->getData();
            $em->persist($typeent);
            $em->flush();

            return $this->redirect("/typeent");
        }
        return $this->render('typeent/EditEt.html.twig',
            ['form'=>$form->createView()]);
    }
    /**
     * @Route ("deleteTe/{id}", name="deleteTe")
     */
    public function deleteQuest(Typeent $typeent, EntityManagerInterface $em){

        $em->remove($typeent);
        $em->flush();
        return $this->redirect("/typeent");
    }
}

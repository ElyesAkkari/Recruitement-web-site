<?php

namespace App\Controller;
use App\Entity\Categories;


use App\Data\SearchData;
use App\Form\AjoutercategorieType;
use App\Form\ModifiercategorieType;
use App\Form\SearchForm;
use App\Repository\FormationRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    /**
     * @Route("/ajoutercategories", name="ajoutercategories")
     */
    public function ajoutercategorie (Request $request): response
{
    $categorie = new Categories();
    $form = $this->createForm(AjoutercategorieType::class, $categorie); // nom de la form : ajouterformateur

    $form->handleRequest($request);

    if ($form->isSubmitted()) {

        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        return $this->redirectToRoute('les categories'); //route de de la template de l'affichage pour faire un lien avec ajouter
    }

    return $this->render(
        'categories/ajoutercategorie.html.twig',
        array('form' => $form->createView())
    );
}
    /**
     * @Route("/suprimercategorie/{id}", name="suprimercategorie")
     */

    public function suprimer ($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('App\Entity\Categories')->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('les categories');


    }
    /**
     * @Route("/affichertablecategorie", name="les categories")
     */

     public function afiichage()
    {
        $categories = $this->getDoctrine()->getRepository('App\Entity\Categories')->findAll();

        return $this->render('categories/categorie.html.twig', ['categorie' => $categories ,
            ]);

    }
    /**
     * @Route("/modifiercategorie/{id}", name="modifiercategorie")
     */
   public function modifier1(Request $request, $id, FlashyNotifier $flashy): response
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('App\Entity\Categories')->find($id);


        if (!$categorie) {
            throw $this->createNotFoundException(
                'There are no formation with the following id: ' . $id
            );
        }

        $form = $this->createForm(ModifiercategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $flashy->success('votre categories est modifié avec succès !' );

            return $this->redirectToRoute('les categories');


        }

        return $this->render(
            'categories/modifiercategorie.html.twig',
            array('form' => $form->createView())
        );
    }
}

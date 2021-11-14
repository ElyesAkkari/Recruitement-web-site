<?php

namespace App\Controller;
use App\Entity\Formateur;
use App\Entity\Formation;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AjouterformateurType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierformateurType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use symfony\Component\Serializer\annotaion\groups;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class FormateurController extends AbstractController
{
    /**
     * @Route("/formateur", name="formateur")
     */
    public function index(): Response
    {
        return $this->render('formateur/index.html.twig', [
            'controller_name' => 'FormateurController',
        ]);
    }

    /**
     * @Route("/Ajouterformateur", name="ajouterF")
     */
    public function AjouterFormateur(Request $request,FlashyNotifier $flashy): response
    {
        $formateur = new Formateur();
        $form = $this->createForm(AjouterformateurType::class, $formateur); // nom de la form : ajouterformateur

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$formateur->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em = $this->getDoctrine()->getManager();
            $formateur->setImage($filename);
            $em->persist($formateur);
            $em->flush();
            $flashy->success('votre formateur est ajouté avec succès !' );



            return $this->redirectToRoute('les_formateurs');

        }

        return $this->render(
            'formateur/ajouterformateur.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/modifierformateur/{id}", name="modifierF")
     */
    public function modifierformateur(Request $request, $id,FlashyNotifier $flashy): response
    {
        $em = $this->getDoctrine()->getManager();
        $formateur = $em->getRepository('App\Entity\Formateur')->find($id);


        if (!$formateur) {
            throw $this->createNotFoundException(
                'There are no formation with the following id: ' . $id
            );
        }

        $form = $this->createForm(ModifierformateurType::class, $formateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$formateur->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $formateur->setImage($filename);
            $em->flush();
            $flashy->success('votre formateur est modifié avec succès !' );

            return $this->redirectToRoute('les_formateurs');


        }

        return $this->render(
            'formateur/modifierformateur.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/affichertableformateurs", name="les_formateurs")
     */
    public function afiicherformateur()
    {
        $formateurs = $this->getDoctrine()->getRepository('App\Entity\Formateur')->findAll();
        return $this->render('formateur/formateurs.html.twig', ['formateurs' => $formateurs]);

    }

    /**
     * @Route("/suprimerformateur/{id}", name="suprimerF")
     */
    public function suprimerformateur($id,FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $formateur = $em->getRepository('App\Entity\Formateur')->find($id);

        if (!$formateur) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $em->remove($formateur);
        $em->flush();
        $flashy->error('votre formateur est supprimé  !' );

        return $this->redirectToRoute('les_formateurs');


    }

    /**
     * @Route("/searchformateur ", name="searchformateur")
     */
    public function searchFormateur(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Formateur::class);
        $requestString = $request->get('searchValue');
        $formateurs = $repository->findFormateurByid($requestString);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($formateurs, 'json',[
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;

    }

}




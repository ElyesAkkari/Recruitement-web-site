<?php

namespace App\Controller;
use App\Data\SearchData;
use App\Entity\Formation;
use App\Entity\Categories;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCodeBundle\Response\QrCodeResponse;


use App\Form\AjouterinformationType;
use App\Form\SearchForm;
use App\Repository\FormationRepository;
use Endroid\QrCode\Builder\BuilderInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierformationType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use symfony\Component\Serializer\annotaion\groups;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="formation")
     */
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'formation' => 'FormationController',
        ]);
    }

    /**
     * @Route("/ajouterFormation", name="ajouterf")
     */
    public function ajouter(Request $request, FlashyNotifier $flashy): response
    {
        $formation = new Formation();

        $form = $this->createForm(AjouterinformationType::class, $formation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $file=$formation->getImage();
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
            $formation->setImage($filename);


            $em->persist($formation);


            $em->flush();
            $flashy->success('votre formation est ajouté avec succès !' );


            return $this->redirectToRoute('les_formations');
        }

        return $this->render(
            'formation/ajouter.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/modifierformation/{id}", name="modifier")
     */
    public function modifier1(Request $request, $id, FlashyNotifier $flashy): response
    {
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository('App\Entity\Formation')->find($id);


        if (!$formation) {
            throw $this->createNotFoundException(
                'There are no formation with the following id: ' . $id
            );
        }

        $form = $this->createForm(ModifierformationType::class, $formation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $file=$formation->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $formation->setImage($filename);


            $em->flush();
            $flashy->success('votre formation est modifié avec succès !' );

            return $this->redirectToRoute('les_formations');


        }

        return $this->render(
            'formation/modifier.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/affichertableformation", name="les_formations")
     */

    public function afiichage():Response
    {
        $formations = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();
    //   $categorie = $formations->getCategories()->getNom();



        return $this->render('formation/formations.html.twig', ['formations' => $formations]
            );

    }
    /**
     * @Route("/suprimerformation/{id}", name="suprimer")
     */

    public function suprimer($id)
    {
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository('App\Entity\Formation')->find($id);

        if (!$formation) {
            throw $this->createNotFoundException(
            'There are no articles with the following id: ' . $id
        );
    }

        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('les_formations');


    }
    /**
     * @Route("/frontformation", name="frontformation")
     */

    public function afiichagefront(FormationRepository $repository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $formations = $repository->findSearch($data);

        foreach ($formations as $a ) {
            $data="".PHP_EOL;
            $data = $data . $a->getNom().PHP_EOL;
            $data = $data . $a->getPrix().PHP_EOL;

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data('formation: '.$data)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->labelText('Scanner le ticket')
                ->labelFont(new NotoSans(20))
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();
            header('Content-Type: '.$result->getMimeType());

            $result->saveToFile($this->getParameter('images_directory').'/'.$a->getId().$a->getNom().'.png');
        }



        return $this->render('formation/formationsfront.html.twig', [
            'formations' => $formations,
            'form' => $form->createView()

        ]);


    }
    /**
     * @Route("/searchformation ", name="searchformation")
     */
    public function searchFormation(Request $request, FormationRepository $repository, NormalizerInterface $Normalizer)
    {

        $requestString = $request->get('searchValue');
        if(strlen($requestString)==0)
        {
            $formations = $repository->findAll();
        }
        else
        {
            $formations = $repository->findFormationByid($requestString);
        }

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($formations, 'json',[
            'ignored_attributes' => ['nomformateur','commantaires'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;

    }


}

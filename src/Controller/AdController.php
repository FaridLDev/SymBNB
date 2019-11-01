<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
           'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une anonnce
     *
     * @Route("/ads/new", name="ads_create")
     *
     */
    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        //$request pour récupérer données formulaire
        //handleRequest pour parcourire les données dans $request
        $form->handleRequest($request);

        //Vérification champs formulaire
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            //Enregistrement dans le DB en utilisant injection de dépendances
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show',[
                    'slug' => $ad->getSlug()
                ]);
        }

        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficer le formulaire d'édition
     * Modiication d'annonces
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AdType::class, $ad);

        //$request pour récupérer données formulaire
        //handleRequest pour parcourire les données dans $request
        $form->handleRequest($request);

        //Vérification champs formulaire
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            //Enregistrement dans le DB en utilisant injection de dépendances
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong>ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/edit.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     */
    public function show(Ad $ad){
       return $this->render('ad/show.html.twig',[
               'ad' => $ad
           ]);
    }


}

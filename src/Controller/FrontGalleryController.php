<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontGalleryController extends AbstractController
{
    #[Route('/galeries', name: 'app_front_galleries')]
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('front_gallery/index.html.twig', [
            'active_menu' => 'gallery',
            'galleries' => $galleryRepository->findAll()
        ]);
    }

    #[Route('/galerie/{slug}', name: 'app_front_gallery')]
    public function showGallery(GalleryRepository $galleryRepository, $slug): Response
    {
        if (!$slug) {
            $gallery = $galleryRepository->findOneBy(['id' => 1]);
        } else {
            $gallery = $galleryRepository->findOneBy(['slug' => $slug]);
        }

        return $this->render('front_gallery/index.html.twig', [
            'active_menu' => 'gallery',
            'gallery' => $gallery,
            'galleries' => $galleryRepository->findAll()
        ]);
    }
}

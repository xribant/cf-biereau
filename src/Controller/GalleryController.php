<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Image;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/galerie')]
class GalleryController extends AbstractController
{
    #[Route('/', name: 'app_gallery_index', methods: ['GET'])]
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('admin/gallery/index.html.twig', [
            'galleries' => $galleryRepository->findAll(),
        ]);
    }

    #[Route('/nouvelle', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GalleryRepository $galleryRepository): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('gallery_image_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);
                $img->setCreatedAt(new \DateTime());
                $gallery->addImage($img);
            }

            $gallery->setUid(uniqid());
            $galleryRepository->add($gallery);
            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/modifier/{uid}', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery, GalleryRepository $galleryRepository): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('gallery_image_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);
                $img->setCreatedAt(new \DateTime());
                $gallery->addImage($img);
            }

            $gallery->setUpdatedAt(new \DateTime());
            $galleryRepository->add($gallery);
            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery, GalleryRepository $galleryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getUid(), $request->request->get('_token'))) {
            $images = $gallery->getImages();
            foreach($images as $image) {
                // On récupère le nom de l'image
                $nom = $image->getName();
                // On supprime le fichier
                unlink($this->getParameter('gallery_image_directory').'/'.$nom);
            }
            $galleryRepository->remove($gallery);
        }

        return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/supprime/image/{id}", name="app_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request, ImageRepository $imageRepository) {
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('gallery_image_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $imageRepository->remove($image);

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\WishRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/wishes', name: 'wishes_')]
final class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list( WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findIdeas();
        return $this->render('wishes/list.html.twig', [
            'wishes' => $wishes,
        ]
        );
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images/wish')] string $imagesDirectory
    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishForm::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $imageFile = $wishForm->get('imageFilename')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image.');
                    // Optionally handle exception or return
                }

                $wish->setImageFilename($newFilename);
            }

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Your wish has successfully been created!');

            return $this->redirectToRoute('wishes_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wishes/create.html.twig', ['wishForm' => $wishForm->createView()]);
    }
    #[Route('/{id}/delete', name: 'delete', methods: ['GET','POST'])]
    public function delete(Wish $wish, EntityManagerInterface $em, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$wish->getId(), $request->get('_token'))){}
        try {
            $em->remove($wish);
            $em->flush();
            $this->addFlash('success', 'Wish has successfully been deleted!');
        } catch(\Exception $e){
            $this->addFlash('error', 'Failed to delete wish.');
        }
        return $this->redirectToRoute('wishes_list');
    }
    #[Route('/{id}', name: 'detail', methods: ['GET'])]
    public function show(Wish $wish, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($wish);
        if(!$wish){
            throw $this->createNotFoundException();
        }
        return $this->render('wishes/detail.html.twig', ['wish' => $wish]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Wish $wish,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images/wish')] string $imagesDirectory): Response
    {
        $wishForm = $this->createForm(WishForm::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $deleteImage = $request->request->get('deleteImage');
            $imageFile = $wishForm->get('imageFilename')->getData();

            if ($deleteImage) {
                $filesystem = new Filesystem();
                $imagePath = $imagesDirectory . '/' . $wish->getImageFilename();

                if ($wish->getImageFilename() && $filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }

                $wish->setImageFilename(null);
            }

            elseIf ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image.');
                    // Optionally handle exception or return
                }

                $wish->setImageFilename($newFilename);
            }

            $imageFile = $wishForm->get('imageFilename')->getData();
            $em->flush();
            $this->addFlash('success', 'Your wish has successfully been updated!');
            return $this->redirectToRoute('wishes_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wishes/edit.html.twig', [
            'wishForm' => $wishForm,
            'wish' => $wish,
        ]);
    }



}

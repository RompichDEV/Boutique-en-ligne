<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(ProduitsRepository $produitsRepository): Response
    {
        $product = $produitsRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'produits' => $product,
        ]);
    }

    #[Route('/produit/create', name: 'create')]
    public function admin(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $product = new Produits();
        $productForm = $this->createForm(ProduitType::class, $product);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){
            $file = $productForm->get('image')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // A gérer avec une exception, cf. la doc
                $file->move($this->getParameter('upload_champ_entite_dir'), $newFilename);
            }

            $product->setImage($newFilename);
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produits ajoutée !');
            return $this->redirectToRoute('admin_produit', ['id' => $product->getId()]);
        }

        return $this->render('admin/admin.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/produit', name: 'produit')]
    public function produitsAdmin(ProduitsRepository $produitsRepository): Response
    {
        $product = $produitsRepository->findAll();

        return $this->render('admin/produitsAdmin.html.twig', [
            'produits' => $product,
        ]);
    }

    #[Route('/produit/edition/{id}', name: 'edit')]
    public function produitsEdit(Produits $product,Request $request,
                                 EntityManagerInterface $entityManager
    ): Response
    {
        $productForm = $this->createForm(ProduitType::class, $product);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produits ajoutée !');
            return $this->redirectToRoute('admin_produit', ['id' => $product->getId()]);
        }

        return $this->render('admin/edit.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }
}

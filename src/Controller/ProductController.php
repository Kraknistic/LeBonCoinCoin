<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\UserLogin;
use App\Form\CreateProductFormType;
use App\Form\SearchProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
class ProductController extends AbstractController
{

    /**
     * @Route("/shop",name="shop")
     **/
    public function categories(EntityManagerInterface $em) {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_ANONYMOUSLY ')) {
            dd("connecetd");
        }
        $repository = $em->getRepository(Product::class);
        $products = $repository->findAll();
        if(!$products) {
            throw $this->createNotFoundException('Sorry, there is no product');
        }
        return $this->render('categories.html.twig', [
            "products" => $products
        ]);
    }

    /**
     * @Route("/product/{productId}",name="productDetails")
     **/
    public function productDetails(EntityManagerInterface $em, $productId) {
        $repository = $em->getRepository(Product::class);
        $product = $repository->find($productId);
        $repository = $em->getRepository(UserLogin::class);
        $userid = $product->getUserId();
        $user = $repository->find($userid);
        if(!$product) {
            throw $this->createNotFoundException('Sorry, there is no product with this id');
        }
        return $this->render('productDetails.html.twig', [
            "product" => $product,
            "user" => $user
        ]);
    }
    /**
     * @Route("/maps/{ville}/{pays}",name="maps")
     **/
    public function maps($ville, $pays) {
        return $this->render('maps.html.twig', [
            "ville" => $ville,
            "pays" =>$pays
        ]);
    }


    /**
     * @Route("/publishproduct", name="publishproduct")
     */
    public function CreateProduct(Request $request, EntityManagerInterface $em, Security $security)
    {
        $product = new Product();
        $form = $this->createForm(CreateProductFormType::class,$product);
        $form->handleRequest($request); // On récupère le formulaire envoyé dans la requête
        if ($form->isSubmitted() && $form->isValid()) { // on véfifie si le formulaire est envoyé et si il est valide

            $article = $form->getData(); // On récupère l'article associé
            $article->setUserId($security->getUser());
            $article->setPublishdate(New \DateTime());
            /*
            $file = $article->getFileName();
            //dd($article);
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $article->setFileName($filename);*/
            $article->setFileName("eztfgdzehn");
            $em->persist($article); // on le persiste
            $em->flush(); // on save
            return $this->redirectToRoute('index'); // Hop redirigé et on sort du controller
        }
        if( !$security->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('publishproduct.html.twig', ['form' => $form->createView()]); // on envoie ensuite le formulaire au template
    }
    /**
     * @Route("/searchproduct", name="searchproduct")
     */
    public function SearchProduct(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(SearchProductFormType::class);
        $form->handleRequest($request); // On récupère le formulaire envoyé dans la requête
        //dd($form);
        if ($form->isSubmitted() && $form->isValid()) { // on véfifie si le formulaire est envoyé et si il est valide
            $repository = $em->getRepository(Product::class);
            $article = $form->getData(); // On récupère l'article associé
            $name = $article["recherche"];
            $names = $repository->findByName($name);
            if(!$names) {
                throw $this->createNotFoundException('Sorry, there is no product with this name');
            }
            dd($names);
            return $this->render('test.html.twig', ['form' => $form->createView(), 'name'=>$name]); // Hop redirigé et on sort du controller
        }
        return $this->render('test.html.twig', ['form' => $form->createView()]); // on envoie ensuite le formulaire au template
    }
}
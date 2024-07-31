<?php

class DefaultController extends AbstractController
{
    public function __construct() {
 
        parent::__construct();
    }


    public function homePage() : void
    {
        $this->render('front/home.html.twig', []);
    }

    public function showInscriptionForm() : void
    {
        $this->render('front/register.html.twig', []);
    }

 
    public function processInscription() : void
    {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';


        if ($username && $password) {

            header('Location: index.php?route=connexion');
            exit;
        }

        
        $this->render('front/register.html.twig', ['error' => 'Veuillez remplir tous les champs.']);
    }


    public function showConnexionForm() : void
    {
        $this->render('front/login.html.twig', []);
    }

 
    public function processConnexion() : void
    {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username && $password) {

            header('Location: index.php?route=home');
            exit;
        }

     
        $this->render('front/login.html.twig', ['error' => 'Veuillez remplir tous les champs.']);
    }

    // Traitement de la déconnexion
    public function processLogout() : void
    {

        header('Location: index.php?route=home');
        exit;
    }

    // Page 404 non trouvée
    public function notFound() : void
    {
        $this->render('front/error404.html.twig', []);
    }
}
<?php

class Router {

    private AuthController $ac;
    private DefaultController $dc;
    private AdminController $adc;
    private UserController $uc; // Ajoutez le contrôleur utilisateur pour les opérations admin

    public function __construct() {
        $this->dc = new DefaultController();
        $this->ac = new AuthController();
        $this->adc = new AdminController(); // Initialisez le contrôleur admin
        $this->uc = new UserController(); // Initialisez le contrôleur utilisateur
    }

    public function handleRequest(?string $route): void {
        if ($route === null) {
            $this->dc->homePage();
            echo "Je dois afficher la page d'accueil";
        } elseif ($route === 'inscription') {
            $this->ac->register();
            echo "Je dois afficher le formulaire d'inscription";
        } elseif ($route === 'check-inscription') {
            $this->ac->checkRegister();
            echo "Je dois traiter le formulaire d'inscription";
        } elseif ($route === 'connexion') {
            $this->ac->login();
            echo "Je dois afficher le formulaire de connexion";
        } elseif ($route === 'check-connexion') {
            $this->ac->checkLogin();
            echo "Je dois traiter le formulaire de connexion";
        } elseif ($route === 'deconnexion') {
            $this->ac->logout();
            echo "Je dois traiter la déconnexion";

        // Routes administratives
        } elseif ($route === 'admin') {
            $this->adc->home();
            echo "Je dois afficher le tableau de bord admin";
        } elseif ($route === 'admin-connexion') {
            $this->adc->login();
            echo "Je dois afficher le formulaire de connexion admin";
        } elseif ($route === 'admin-check-connexion') {
            $this->adc->checkLogin();
            echo "Je dois traiter le formulaire de connexion admin";

        
        } elseif ($route === 'admin-create-user') {
            $this->uc->create();
            echo "Je dois afficher le formulaire de création d'utilisateur admin";
        } elseif ($route === 'admin-check-create-user') {
            $this->uc->checkCreate();
            echo "Je dois traiter le formulaire de création d'utilisateur admin";
        } elseif ($route === 'admin-edit-user') {
            $this->uc->edit();
            echo "Je dois afficher le formulaire de modification d'utilisateur admin";
        } elseif ($route === 'admin-check-edit-user') {
            $this->uc->checkEdit();
            echo "Je dois traiter le formulaire de modification d'utilisateur admin";
        } elseif ($route === 'admin-delete-user') {
            $this->uc->delete();
            echo "Je dois traiter la suppression d'utilisateur admin";
        } elseif ($route === 'admin-list-users') {
            $this->uc->list();
            echo "Je dois afficher la liste des utilisateurs admin";
        } elseif ($route === 'admin-show-user') {
            $this->uc->show();
            echo "Je dois afficher les détails d'un utilisateur admin";
        } else {
            $this->dc->notFound();
            echo "Je dois afficher la page 404";
        }
    }
}
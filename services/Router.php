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
            echo "Je dois afficher l'utilisateur récupéré";
        } elseif ($route === 'deconnexion') {
            $this->ac->logout();
            echo "Je dois traiter la déconnexion";

        // Routes admin
        } elseif ($route === 'admin') {
            $this->checkAdminAccess();
            $this->adc->home();
            echo "Je dois afficher le tableau de bord admin";

        } elseif ($route === 'admin-connexion') {
            $this->adc->login();
            echo "Je dois afficher le formulaire de connexion admin";

        } elseif ($route === 'admin-check-connexion') {
            $this->adc->checkLogin();
            echo "Je dois traiter le formulaire de connexion admin";

        
        } elseif ($route === 'admin-create-user') {
            $this->checkAdminAccess();
            $this->uc->create();
            echo "Je dois afficher le formulaire de création d'utilisateur admin";

        } elseif ($route === 'admin-check-create-user') {
            $this->checkAdminAccess();
            $this->uc->checkCreate();
            echo "Je dois traiter le formulaire de création d'utilisateur admin";

        } elseif ($route === 'admin-edit-user') {
            $this->checkAdminAccess();
            if(isset($_GET['user_id']))
                $userId = intval($_GET['user_id']);
            $this->uc->edit($userId);
            echo "Je dois afficher le formulaire de modification d'utilisateur admin";

        } elseif ($route === 'admin-check-edit-user') {
            $this->checkAdminAccess();
            $this->uc->checkEdit();
            echo "Je dois traiter le formulaire de modification d'utilisateur admin";

        } elseif ($route === 'admin-delete-user') {
            $this->checkAdminAccess();
            if (isset($_GET['user_id'])) {
                $userId = intval($_GET['user_id']);
                $this->uc->delete($userId);
                echo "Je dois traiter la suppression d'utilisateur admin";
            } else {
                header('Location: /admin/users/list');
                exit;
            }
        } elseif ($route === 'admin-list-users') {
            $this->checkAdminAccess();
            $this->uc->list();
            echo "Je dois afficher la liste des utilisateurs admin";

        } elseif ($route === 'admin-show-user') {
            $this->checkAdminAccess();
                if(isset($_GET['user_id']))
                $userId = intval($_GET['user_id']);
            $this->uc->show($userId);
            echo "Je dois afficher les détails d'un utilisateur admin";


            // le code si c'est aucun des cas précédents ( === page 404)
        } else {
            $this->dc->notFound();
            echo "Je dois afficher la page 404";
        }
    }
    private function checkAdminAccess(): void
    {
        if(isset($_SESSION['user']) 
            && isset($_SESSION['role']) && $_SESSION['role'] === "ADMIN")
            {
           
            }
            else
            {
                     // c'est pas bon : redirection avec un header('Location:')
                     $this->redirect("admin-connexion");
            }
    }
    
    protected function redirect(? string $route) : void 
    {
        if($route !== null)
        {
            header("Location: index.php?route=$route");
        }
        else
        {
            header("Location: index.php");
        }
        exit();
    }   
}
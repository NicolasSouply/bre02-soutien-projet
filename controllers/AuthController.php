<?php

class AuthController extends AbstractController {

    private UserManager $um;
    private CSRFTokenManager $csrfTokenManager;

    public function __construct() {
        parent::__construct();
        $this->um = new UserManager();
        $this->csrfTokenManager = new CSRFTokenManager(); 
    }

    public function register() : void {
       
        $this->render('front/register.html.twig', []);
    }
    public function login() : void {
        $this->render('front/login.html.twig',[]);
}
    public function checkRegister() : void {

        if (!isset($_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['csrf_token'])) {
            $this->redirectWithError('index.php?route=inscription', 'Tous les champs sont obligatoires.');
            return;
        }
    
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $csrfToken = $_POST['csrf_token'];
    
    
        if (!$this->csrfTokenManager->validateCSRFToken($csrfToken)) {
            $this->redirectWithError('index.php?route=inscription', 'Le token CSRF est invalide.');
            return;
        }
    

        if ($this->um->findUserByEmail($email)) {
            $this->redirectWithError('index.php?route=inscription', 'Un compte avec cet email existe déjà.');
            return;
        }
    
    
        if ($password !== $confirmPassword) {
            $this->redirectWithError('index.php?route=inscription', 'Les mots de passe ne correspondent pas.');
            return;
        }
    

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new Users($email, $hashedPassword, "USER");
    

        $this->um->createUser($user);
    
   
        $this->redirect('connexion');
    }
    public function checkLogin() : void {

        if (!isset($_POST['email'], $_POST['password'], $_POST['csrf_token'])) {
            $this->redirectWithError('login.php', 'Tous les champs sont obligatoires.');
            return;
        }
    
        $email = $_POST['email'];
        $password = $_POST['password'];
        $csrfToken = $_POST['csrf_token'];
    

        if (!$this->csrfTokenManager->validateCSRFToken($csrfToken)) {
            $this->redirectWithError('login.php', 'Le token CSRF est invalide.');
            return;
        }
    

        $user = $this->um->findUserByEmail($email);
        if (!$user) {
            $this->redirectWithError('login.php', 'Aucun compte trouvé avec cet email.');
            return;
        }
    

        if (!password_verify($password, $user->getPassword())) {
            $this->redirectWithError('login.php', 'Le mot de passe est incorrect.');
            return;
        }
    

        session_start();
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole();
    

        $this->redirect('index.php');
    }
    public function logout() : void {
        // Détruire la session
        session_start();
        session_unset();
        session_destroy();

        // Redirection vers la page d'accueil
        $this->redirect('index.php');
    }

    private function redirectWithError(string $url, string $errorMessage) : void {
        // Stocker le message d'erreur dans la session pour l'afficher après la redirection
        $_SESSION['error'] = $errorMessage;
        header("Location: $url");
        exit();
    }

}

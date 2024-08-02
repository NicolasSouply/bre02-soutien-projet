<?php

class UserController extends AbstractController
{
    private UserManager $um;
    private CSRFTokenManager $csrfTokenManager;
    public function __construct(){
        parent::__construct();
        $this->um = new UserManager(); //j'initialise le userManager pour pouvoir appeler sa méthode
        $this->csrfTokenManager = new CSRFTokenManager(); 
    }

    public function create() : void {
        $this->render("admin/users/create.html.twig", []);
    }

    public function checkCreate() : void {
        if (!isset($_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['role'], $_POST['csrf_token'])) {
            $this->redirectWithError('index.php?route=admin-create-user', 'Tous les champs sont obligatoires.');
            return;
        }
    
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $csrfToken = $_POST['csrf_token'];
        $role = $_POST['role'];
    
    
        if (!$this->csrfTokenManager->validateCSRFToken($csrfToken)) {
            $this->redirectWithError('index.php?route=admin-create-user', 'Le token CSRF est invalide.');
            return;
        }
    

        if ($this->um->findUserByEmail($email)) {
            $this->redirectWithError('index.php?route=admin-create-user', 'Un compte avec cet email existe déjà.');
            return;
        }
    
    
        if ($password !== $confirmPassword) {
            $this->redirectWithError('index.php?route=admin-create-user', 'Les mots de passe ne correspondent pas.');
            return;
        }
    

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new Users($email, $hashedPassword, $role);
    

        $this->um->createUser($user);
    
   
        $this->redirect('admin-list-user');
    }

   

    public function edit(int $id) : void {
        $user = $this->um->findUserById($id);
    
        if ($user !== null) {
            $this->render("admin/users/edit.html.twig", ['user' => $user]);
        } else {
            header('Location: /admin/users/list');
            exit;
        }
    }

    public function checkEdit() : void {

    }

    public function delete(int $id) : void {
        $user = $this->um->findUserById($id);

        header('Location: /admin/users/list');
        exit;
    }
    

    public function list() : void {
        // Je récupère tous les users
        $users = $this->um->findAllUsers();
        $this->render("admin/users/list.html.twig", ['users' => $users]);
    }

    public function show(int $id) : void {
        $user = $this->um->findUserByEmail($id);

        if ($user !== null ) {
            $this->render("admin/users/show.html.twig", ['user' => $user]);
        } else {
            header('location: /admin/users.list');
            exit;
        }
        
    }
    private function redirectWithError(string $url, string $errorMessage) : void {
        // Stocker le message d'erreur dans la session pour l'afficher après la redirection
        $_SESSION['error'] = $errorMessage;
        header("Location: $url");
        exit();
    }
}

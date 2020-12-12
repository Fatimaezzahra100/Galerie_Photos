<?php
class Gallery extends Controller {
  public function index() {
    $this->albums();
  }
  

  public function albums() {
    $albums = $this->gallery->albums();
    $this->loader->load('albums',['title' => 'Albums', 'albums'=>$albums]); 
  }

  public function albums_new() {
    $this->loader->load('albums_new',['title'=>'créationd\'un nouvel album']);
    
  }
  
  public function albums_create() {
      try {
        // à revoir dans la documentation!
        $album_name = filter_input(INPUT_POST, 'album_name');
        /* Créer l'album avec le modèle. */
        $this->gallery->create_album($album_name);
        /* redirection du client vers la liste des albums. */ 
        header('Location: /galerie/index.php/gallery/albums');
      } catch (Exception $e) {
        $this->loader->load('albums_new', ['title'=>'Création d\'un album', 'error_message' => $e->getMessage()]);
      }
  }
  
  public function albums_delete($album_name) {
    try {
      $name = filter_var($album_name);
      $this->gallery->delete_album($album_name);
    } catch (Exception $e) { }
    header('Location: /galerie/index.php/gallery/albums');
  }

  public function albums_show($album_name) {
    try {
      $this->loader->load('albums_show', 
             ['title'=>$album_name, 
             'album'=> $album_name, /* TODO : nom de l'album */
             /* TODO : tableau avec les informations sur les photos */
            'photos'=> $this->gallery->photos($album_name)]);
    } catch (Exception $e) {
      header("Location: /galerie/index.php");
    }
  }
  
  public function photos_new($album_name) {
    $this->loader->load('photos_new', ['album_name'=>$album_name, 'title'=>'Ajout d\'une nouvelle photo']);
  }

  public function photos_add($album_name) {
    try {
      $album_name = filter_var($album_name);
      /* TODO : vérifier si l'album existe. */
      $this->gallery->check_if_album_exists ( $album_name );
    } catch (Exception $e) { header("Location: /galerie/index.php"); }
  
    try {
      $photo_name = filter_input(INPUT_POST, 'photo_name');
      if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Vous devez choisir une photo.');
      }
      /* TODO : demander au modèle d'ajouter la photo dont le nom 'temporaire' du fichier
                est donné par $_FILES['photo']['tmp_name']; */
         $this->gallery->add_photo($album_name, $photo_name, $_FILES['photo']['tmp_name']);

      /* TODO : rediriger l'utilisateur vers l'affichage des photos de l'album,
                c'est-à-dire vers l'URL "/index.php/gallery/albums_show/$album_name"; */
                header("Location: /galerie/index.php/gallery/albums_show/$album_name");
    } catch (Exception $e) {
      $this->loader->load('photos_new', ['album_name'=>$album_name,
                          'title'=>"Ajout d'une photo dans l'album $album_name", 
                                 'error_message' => $e->getMessage()]);
    }
  }
  

  public function photos_delete($album_name, $photo_name) {
    try {
      $name = filter_var($album_name);
      $this->gallery->delete_photo($album_name, $photo_name);
    } catch (Exception $e) { }
    header("Location: /galerie/index.php/gallery/albums_show/$album_name");
  }
  
  public function photos_show($album_name, $photo_name) {
     try {
    $album_name = filter_var($album_name);
    $photo_name = filter_var($photo_name);
    $this->loader->load('photos_show', ['title'=>"$album_name / $photo_name",
        'album'=> $album_name /* TODO : nom de l'album */,
        'photo'=> $this->gallery->photo($album_name, $photo_name) /* TODO : description de la photo */
    ]);
  } catch (Exception $e) {
    header("Location: /index.php");
  }
  }
}
<div class="row">
  <?php foreach ($photos as $photo) { ?>
    <div class="col-sm-3 col-md-3">
      <div class="thumbnail">
        <a class="close" href="/galerie/index.php/gallery/photos_delete/<?=$album?>/<?=$photo['photo_name']?>">×</a>
        <br> <br> 
        <a style="height: 200px; width: 400px; display: table-cell; vertical-align: middle; text-align: center;"
           href="/galerie/index.php/gallery/photos_show/<?=$album?>/<?=$photo['photo_name']?>">
          <img src="/<?=$photo['thumbnail_path']?>" alt="<?=$photo['photo_name']?>">
        </a>
        <div class="caption text-center"><?=$photo['photo_name']?><br></div>
      </div>
    </div>
  <?php } ?>
</div>

<a href="/galerie/index.php/gallery/photos_new/<?=$album?>"  class="btn btn-primary" role="button">Ajouter une photo</a>
<a href="/galerie/index.php" class="btn btn-danger" role="button">Revenir à la liste des albums</a>
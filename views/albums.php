<table class="table">
<?php foreach ($albums as $album) { ?>
 <tr>
    <td class="text-left">
      <a class="btn btn-default" href="galerie/index.php/gallery/albums_show/<?=$album?>"><?= $album ?></a>
    </td>
    <td class="text-right">
      <a class="btn" href="/galerie/index.php/gallery/albums_delete/<?=$album?>">
        <i class="glyphicon glyphicon-trash"></i>
      </a>
    </td>
  </tr>
<?php } ?>
</table>

<a href="/galerie/index.php/gallery/albums_new" class="btn btn-primary"
   role="button">Créer un nouvel album</a> 
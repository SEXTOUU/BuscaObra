<?php
  
  if(isset($_FILES["imagem"]) && !empty($_FILES["imagem"]))
  {
    move_uploaded_file($_FILES["imagem"]["tmp_name"], "./img/".$_FILES["imagem"]["name"]);
    echo "update realizado!!!";
  }  
?>


</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="row">
    <div class="col-md4">
        <form action="./upload.php" method="post" enctype="multipart/form-data">
            <label>selecione uma imagem</label>
            <input type="file" name="imagem" accept="image/*" class="form-control">
            <button type="submit" class="bnt bnt bnt-success">
                Enviar imagem 
            </button>
            <img src="img/download.jpg" alt="">
        </form>
    </div>
</body>
</html>
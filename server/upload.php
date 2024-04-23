<form action="<?php echo $_SERVER["PHP_SELF"];  ?>" method="post" enctype="multipart/form-data" id="something" class="uniForm">
    <input name="new_image" id="new_image" size="30" type="file" class="fileUpload" />
    <button name="submit" type="submit" class="submitButton">Upload Image</button>
</form>

<?php
    if(isset($_POST['submit'])){
        if (isset ($_FILES['new_image'])){
            $imagename = $_FILES['new_image']['name'];
            $source = $_FILES['new_image']['tmp_name'];
            $target = "images/".$imagename;
            $type=$_FILES["new_image"]["type"];

            if($type=="image/jpeg" || $type=="image/jpg"){
                move_uploaded_file($source, $target);



// Crea un oggetto Imagick dall'immagine JPEG di input
$image = new Imagick($target);

// Resize dell'immagine mantenendo l'aspetto originale
$image->resizeImage(800, 600, Imagick::FILTER_LANCZOS, 1, true);

// Crop per adattare l'immagine alla dimensione esatta 800x480 pixel
$image->cropImage(800, 480, 0, 60); // (larghezza, altezza, x, y)

// Definisci i colori della palette personalizzata
$colors = [
    'rgb(0, 0, 0)',          // Nero
    'rgb(255, 255, 255)',    // Bianco
 'rgb(0, 128, 0)',        // Verde
 'rgb(0, 0, 255)',        // Blu
    'rgb(255, 0, 0)',        // Rosso
    'rgb(255, 255, 0)',      // Giallo
    'rgb(255, 170, 0)'      // Magenta

];


// Creiamo una nuova immagine vuota con la palette personalizzata
$paletteImage = new Imagick();
$paletteImage->newImage(1, count($colors), new ImagickPixel('transparent'));

$draw  = new ImagickDraw();
// Impostiamo i colori della palette
foreach ($colors as $index => $color) {
    $pixel = new ImagickPixel($color);
   // $paletteImage->setColorPixel($index, 0, $pixel);
   
    $draw->setFillColor($color);
    $draw->point(0,$index);
}

$paletteImage->drawImage($draw);

// Crea una nuova immagine con una palette personalizzata
//$image->quantizeImage(count($colors), Imagick::COLORSPACE_RGB, 0, false, false);
$image->remapImage($paletteImage, Imagick::DITHERMETHOD_RIEMERSMA);

// Imposta il formato dell'immagine di output come BMP
$image->setImageFormat('BMP');

// Salvataggio dell'immagine convertita nel file di output
$image->writeImage($target.".bmp");
// cancello il jpeg originale
unlink($target);

$image->destroy();

  echo "Large image: <img src='".$target.".bmp'><br>";

            } else{
                echo "File is not image";
            }
      }
    }
?>
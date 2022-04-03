<?php

//phpinfo();  // Shows all data about the server we host files to

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $article = Article::getById($conn, $_GET['id']);

    if (! $article) {
        die('article not found');
    }

} else {
    
    die('id not supplied. Article not found');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {

        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploded');
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File size too large (from the server settings)');
            default:
                throw new Exception('An error occurred');
        }

        if ($_FILES['file']['size'] > 1000000) {
            throw new Exception('File is too large');
        }

        // Create an array containing most common image mime types
        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

        // Use finfo_ function to reliably determine mime type of a file
        // Creates a new file info instance of type MIME_TYPE
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        
        // Returns a string containing the mime type
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);

        if (! in_array($mime_type, $mime_types)) {
            throw new Exception('Invalid file type');
        }

        // Filter uploaded file name so it does not contain any special characters
        // e.g. a file with name hello#(@!.png will be saved to folder as hello____.png
        
        // Create a pathinfo instance of uploaded file name
        $pathinfo = pathinfo($_FILES['file']['name']);
        
        // Extract file name property (filename without the extension)
        $base = $pathinfo['filename'];
        
        // Replace special chars with _ (using specific char pattern)
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

        // Limit the name of the image file to max 200 characters
        $base = mb_substr($base, 0, 200);
        
        // Create the final filename (adding the filename extension property)
        $filename = $base . "." . $pathinfo['extension'];
        
        // Finally create the full destination path
        $destination = "../uploads/$filename";
        
        // If file exists, add a -1 or -2 or -3 or etc to the new uploaded file
        $i = 1;
        while (file_exists($destination)) {            
            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "../uploads/$filename";
            $i++;
        }

        // Move uploaded file to specific folder
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            // Delete previous image file when uploading another using unlink func
            $previous_image = $article->image_file;
            
            if ($article->setimageFile($conn, $filename)) {

                if ($previous_image) {
                    unlink("../uploads/$previous_image");
                }
                
                Url::redirect("/cms/admin/edit-article-image.php?id={$article->id}");
            }

        } else {

            throw new Exception("Unable to move uploaded file");
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<?php require '../includes/header.php'; ?>

<h1><?=$article->title?></h1>
<h2>Edit article image</h2>

<?php if ($article->image_file) : ?>
    <img src="/cms/uploads/<?=$article->image_file;?>" alt="">
    <a href="delete-article-image.php?id=<?= $article->id;?>">Delete</a>
<?php endif ?>

<?php if (isset($error)) : ?>
    <p><?= $error ?></p>    
<?php endif ?>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="file">Image files</label>        
        <input type="file" name="file" id="file">
    </div>
    <button>Upload</button>
</form>

<?php require '../includes/footer.php'; ?>

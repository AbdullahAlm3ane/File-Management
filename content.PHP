<?php 
    header('Cache-Control: no cache');
    session_cache_limiter('private_no_expire');
    session_start();
    $folder = $_GET['folder'];

    // Read
    $array = array_diff(scandir("data/" . $folder), array('.', '..', '.svn', '.htaccess'));
    $directories = array();
    $files_list  = array();

    foreach ($array as $data) {
        if (is_dir("data/" . $folder . '/' . $data)) {
            $directories[]  = $data;
        } else {
            $files_list[]    = $data;
        }           
    }

    // Upload
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Upload File
        if (isset($_POST["fileSubmit"])) {
            $fileData = $errorFile = "";

            if (!empty($_FILES["fileData"])) {
                $fileData = $_FILES["fileData"];
            } else {
                $errorFile = "Empty File";
            }

            if ($fileData != "") {
                $fileName = $fileData["name"];
                $fileTemp = $fileData["tmp_name"];

                move_uploaded_file(
                    $fileTemp,
                    "./data/{$folder}/{$fileName}",
                );
                
                echo "<meta http-equiv='refresh' content='0'>";
            }
        }
    }

    // Create Folder
    if (isset($_POST["folderSubmit"])) {
        $folderName = "";

        if (!empty($_POST["foldeName"])) {
            $folderName = $_POST["foldeName"];
        }

        if ($folderName != "") {
            mkdir(
                "./data/{$folder}/{$folderName}",
                0777,
                true
            );

            echo "<meta http-equiv='refresh' content='0'>";
        }
    }

    // Delete Folder
    function removeDirectory($path) {
        $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if ($file == $path.'/.' || $file == $path.'/..') { continue; }
            is_dir($file) ? removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<body>
    <!-- Dark Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top B-Gray">
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand  me-auto" href="#">File Management System</a>

            <!-- Right links -->
            <div class="d-flex align-items-center">
            <a id=A class="btn px-3 " href="index.php" role="button"><i class="fas fa-sign-out-alt"></i>     Log Out</a>

            <a id=A class="btn px-3  " href="index.php" role="button"><i class="fas fa-user"></i>      Admin</a>
            </div>
        </div>
    </nav>

    <!-- Light Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand  me-auto" href="#"><i class="fas fa-folder-open" style="color:#4d4d4f;"></i>  File Manager</a>

            <!-- Right links -->
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary me-3 rounded-0" data-bs-toggle="modal" data-bs-target="#newFolder">
                    Create Folder
                </button>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group input-group">
                        <input name="fileData" id="fileData" class="form-control" type="file" style="display: none" onchange="document.getElementById('fileSubmit').click();">

                        <input name="fileSubmit" id="fileSubmit" class="form-control" type="submit" style="display: none">
                    </div>
                </form>

                <button type="button" id="fileButtonSubmit" class="btn btn-primary me-3 rounded-0" onclick="document.getElementById('fileData').click();">Upload File</button>
            </div>
        </div>
    </nav>

    <!-- New Folder Model -->
    <div class="modal fade" id="newFolder">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add a New Folder</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group input-group">
                            <input name="foldeName" class="form-control" placeholder="Folder name" type="text">
                            <input name="folderSubmit" id="folderSubmit" class="form-control" type="submit" style="display: none">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary me-3" onclick="document.getElementById('folderSubmit').click();">Add</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- File Table -->
    <section class="folders">
        <div class="container">
            <div class="card">
                <div class="card-body bg-light">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="p-4" scope="col" style="width:50%">Title/Name</th>
                                <th class="p-4" scope="col">Type</th>
                                <th class="p-4" scope="col">Date Adedd</th>
                                <th class="p-4" scope="col">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Folders
                                foreach ($directories as $data) {
                                    echo '
                                        <tr>
                                            <td scope="row">
                                                <a href="content.php?folder=' . $folder . '/'. $data .'"> 
                                                    <i class="fa fa-folder px-3" style="color:#fccc77;"></i>' . $data .'
                                                </a>
                                            </td>
                                            <td>Folder</td>
                                            <td>'. date("F d Y H:i:s.", fileatime('data/' . $folder . '/' . $data)) .'</td>
                                            <td>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <input name="deleteFolder' . $data .'" class="btn btn-danger px-3" type="submit" value="Delete">
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    ';

                                    // Delete
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        if (isset($_POST['deleteFolder' . $data])) {
                                            removeDirectory('data/' . $folder . '/' . $data);

                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                    }
                                }

                                // Files
                                for ($index=0; $index < count($files_list); $index++) { 
                                    $data = $files_list[$index];

                                    echo '
                                        <tr>
                                            <td scope="row">
                                                <a href="file_content.php?file=data/' . $folder . '/' . $data .'">
                                                    '. pathinfo($data, PATHINFO_FILENAME) .'
                                                </a>
                                            </td>
                                            <td>' . pathinfo($data, PATHINFO_EXTENSION) . '</td>
                                            <td>'. date("F d Y H:i:s.", fileatime('data/' . $folder . '/' . $data)) .'</td>
                                            <td>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <input name="deleteFile'. $index .'" class="btn btn-danger px-3" type="submit" value="Delete">
                                                        
                                                        <a href="data/' . $folder . '/' . $data . '" class="btn btn-secondary px-3" download>
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    ';

                                    // Delete
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        if (isset($_POST['deleteFile'. $index])) {
                                            unlink('data/' . $folder . '/' . $data);

                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer fixed-bottom p-2">
        <p style="color:white; text-align:center;">Created at training with Abdullah</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous">
    </script>
</body>

</html>
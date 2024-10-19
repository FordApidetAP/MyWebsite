<?php 

    include '../components/connect.php';

    
    if (isset($_REQUEST['btn_insert'])) {
        $category = $_REQUEST['txt_category'];
        
        if (empty($category)) {
            $errorMsg = "Please enter Category";
        } else {
            try {
                if (!isset($errorMsg)) {
                    $insert_tbcategory = $conn->prepare("INSERT INTO tbcategory(cCategoryName) VALUES (:category)");
                    $insert_tbcategory->bindParam(':category', $category);

                    if ($insert_tbcategory->execute()) {
                        $insertMsg = "Insert Successfully...";
                        header("refresh:2;category.php");
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php include 'Sidebar.php'; ?>

<div class="col py-3">
    <div class="container">
        <?php 
                if (isset($errorMsg)) {
            ?>
                <div class="alert alert-danger">
                    <strong>Wrong! <?php echo $errorMsg; ?></strong>
                </div>
            <?php } ?>
            

            <?php 
                if (isset($insertMsg)) {
            ?>
                <div class="alert alert-success">
                    <strong>Success! <?php echo $insertMsg; ?></strong>
                </div>
            <?php } ?>

            <form method="post" class="form-horizontal mt-5">
                    
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="Category" class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-9">
                                <input type="text" name="txt_category" class="form-control" placeholder="Enter Category...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center mb-3">
                        <div class="col-md-12 mt-3">
                            <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
                            <a href="category.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>


            </form>
    </div>
</div>
</body>
</html>
<?php

    session_start();
    include 'components/connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>RegisterPage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
@import url('https://fonts.googleapis.com/css?family=Abel|Abril+Fatface|Alegreya|Arima+Madurai|Dancing+Script|Dosis|Merriweather|Oleo+Script|Overlock|PT+Serif|Pacifico|Playball|Playfair+Display|Share|Unica+One|Vibur');
* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body {
    background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
background-attachment: fixed;
  background-repeat: no-repeat;

    font-family: 'Vibur', cursive;
    font-family: 'Abel', sans-serif;
opacity: .95;
}
form {
    width: 480px;
    min-height: 500px;
    height: auto;
    border-radius: 5px;
    margin: 2% auto;
    box-shadow: 0 9px 50px hsla(20, 67%, 75%, 0.31);
    padding: 2%;
    background-image: linear-gradient(-225deg, #E3FDF5 50%, #FFE6FA 50%);
}
#register {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 999;
    text-align: center;
  }
form .con {
    display: -webkit-flex;
    display: flex;
  
    -webkit-justify-content: space-around;
    justify-content: space-around;
  
    -webkit-flex-wrap: wrap;
    flex-wrap: wrap;
  
      margin: 0 auto;
}
header {
    margin: 2% auto 10% auto;
    text-align: center;
}
header h2 {
    font-size: 250%;
    font-family: 'Playfair Display', serif;
    color: #3e403f;
}
header p {
    letter-spacing: 0.05em;
}
.input-item {
    background: #fff;
    color: #333;
    padding: 14.5px 0px 15px 9px;
    border-radius: 5px 0px 0px 5px;
}
#eye {
    background: #fff;
    color: #333;

    margin: 5.9px 0 0 0;
    margin-left: -20px;
    padding-right: 4px;
    border-radius: 0px 5px 5px 0px;

    float: right;
    position: relative;
    right: 5%;
    top: 6%;
    z-index: 5;

    cursor: pointer;
}
input[class="form-input"]{
    width: 240px;
    height: 50px;
  
    margin-top: 2%;
    padding: 15px;
    
    font-size: 16px;
    font-family: 'Abel', sans-serif;
    color: #5E6472;
  
    outline: none;
    border: none;
  
    border-radius: 0px 5px 5px 0px;
    transition: 0.2s linear;
    
}
input[id="txt-input"] {width: 250px;}
input:focus {
    transform: translateX(-2px);
    border-radius: 5px;
}
button {
    display: inline-block;
    color: #252537;
  
    width: 280px;
    height: 50px;
  
    padding: 0 20px;
    background: #fff;
    border-radius: 5px;
    
    outline: none;
    border: none;
  
    cursor: pointer;
    text-align: center;
    transition: all 0.2s linear;
    
    margin: 7% auto;
    letter-spacing: 0.05em;
}
.submits {
    width: 90px;
    height: 50px;
    display: inline-block;
    float: left;
    margin-left: 2%;
    margin-top: 5px;
    padding-top: 15px;
    border-radius: 5px;

    cursor: pointer;
}


.sign-up {background: #B8F2E6;}


.submits:hover {
    transform: translatey(3px);
    box-shadow: none;
}

.submits:hover {
    animation: ani9 0.4s ease-in-out infinite alternate;
}
@keyframes ani9 {
    0% {
        transform: translateY(3px);
    }
    100% {
        transform: translateY(5px);
    }
}

</style>   
<body>
<form action="registerprocess.php" id="register" name="register" method="post">
<?php if(isset($_SESSION['error'])) { ?>
                <?php
                    echo "<script>
                    Swal.fire({
                        title: '".$_SESSION['error']."',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
                unset($_SESSION['error']);
                ?>
            <?php } ?>

            <?php if(isset($_SESSION['success'])) { ?>
                <?php
                    echo "<script>
                    Swal.fire({
                        title: '".$_SESSION['success']."',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
                unset($_SESSION['success']);
                ?>
            <?php } ?>

<div class="con">

              <header  class="head-form">
                <h2>Register</h2>
                <p>Wellcome to Myweb Pls Register!</p>
            </header>
            <br>
            <div class="field-set">

                <span class="input-item">
                    <i class="bi bi-person-fill"></i>
                </span>
                <input class="form-input" type="text" id="username" name="username" placeholder="UserName">

                <br>

                <span class="input-item">
                    <i class="bi bi-envelope-fill"></i>
                </span>
                <input class="form-input" type="email" id="email" name="email" placeholder="Email">

                <br>

                <span class="input-item">
                    <i class="bi bi-key-fill"></i>
                </span>
                <input class="form-input" type="password" id="password" name="password" placeholder="Password">

                <span>
                    <i class="bi bi-eye-fill" aria-hidden="true" type="button" id="eye"></i>
                </span>

                <br>

                <button type="submit" name="register">Submit</button>
            </div>
            <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
         <p style="margin: 0; float:right;">HaveAccount?<br>Click!<i class="bi bi-arrow-right"></i></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <div class="btn submits sign-up" onclick="window.location.href='index.php';">Login</div>
</div>
        </div>
    </form>

<script>

function show() {
    var p = document.getElementById('password');
    p.setAttribute('type', 'text');
}

function hide() {
    var p = document.getElementById('password');
    p.setAttribute('type', 'password');
}

var pwShown = 0;

document.getElementById("eye").addEventListener("click", function () {
    if (pwShown == 0) {
        pwShown = 1;
        show();
    } else {
        pwShown = 0;
        hide();
    }
}, false);


</script>
</body>
</html>
<?php

$targetUrl = "";
$mainUrl = "https://api.themoviedb.org/3/";
function fetch_curl($url){
    //create CURL resource
    $curl = curl_init();

    //set CURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //execute CURL
    $data = curl_exec($curl);
    //var_dump($data);
    //Close
    curl_close($curl);
    return json_decode($data); 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>MovieQuora</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">MovieQuora</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07"
                aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown07" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Dropdown</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown07">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="overlay">
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
      <source src="mp4/Sony-cam.mp4" type="video/mp4">
    </video>
    </div>
    <div class="container">
    <div class="m-md-5">
    <form class="form-inline my-2 my-lg-0 justify-content-center" method="get">
                    <input class="form-control mr-sm-2" type="search" placeholder="Enter a movie or tv show" name="search" size="60" required>
                    <button class="btn btn-outline-light my-2 my-sm-0 bg-primary" type="submit">Search</button>
                </form>
</div>
        <?php
        if ($_SERVER['REQUEST_METHOD']=="GET"){
            if(isset($_GET['search']) && $_GET['search'] !=""){
                $pageNumber = 1;
                $search = $_GET['search'];
                           
                            do{                 
                                $targetUrl = str_replace(" ", "+", $mainUrl . "search/movie?query=" . $search . "&api_key=cc619552d30b59fc7ee1cc2334a37d56&page=" . $pageNumber);
                                $result = fetch_curl($targetUrl);//echo $targetUrl;
                                $totalResult = $result->total_results;
                                if($totalResult == 0){
                                    ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Oups!</strong> No records was founds.
                                    </div>
                                    <?php
                                    break;
                                } 
                                $data = $result->results; // put the results in a variable                             
                                $totalPages = $result->total_pages;

                                for($i = 0; $i<count($result->results); $i++){ ?>
                                  <div class='card'>
                                  <div class='row no-gutters'>
                                  <div class='col-auto'>
                                  <?php
                                    if(isset($data[$i]->poster_path)){?>
                                        <img src='https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php  echo $data[$i]->poster_path ?>' class='img-fluid' alt='poster'>
                                    <?php }else{ ?>
                                        <img src='nophoto.jpg' class='img-fluid' alt='poster'>
                                    <?php } ?>
                                  
                                  </div>
                                
                                  <div class='col-md'>
                                  <div class='row no-gutters'>
                                  <div class='card-body px-2'>
                                      <h4 class='card-title'><?php echo $data[$i]->title ?></h4>
                                      <h6 class ='text-muted'>Release date: <small><?php echo $data[$i]->release_date ?></small></h6>
                                      <p class='card-text'><?php echo  $data[$i]->overview ?></p>
                                      <a href='page3.php?movie_id=<?php echo $data[$i]->id ?>' class='btn btn-primary btn-sm'>Full details</a>
                                      <br>
                                      <br>
                                      <br>
                                      <br>
                                  </div>
                                  </div>

                                  <div class='row no-gutters mt-0 position-absolute fixed-bottom w-100'>
                                  <div class='card-footer w-100 text-muted bg-light'>
                                  <span class='badge badge-warning'>Vote <?php echo 
                                  $data[$i]->vote_average ?></span>&nbsp
                                  <span class='badge badge-secondary'>popularity <?php echo $data[$i]->popularity ?></span>&nbsp
                                  <span class='badge badge-success'>Language <?php echo $data[$i]->original_language ?></span>&nbsp
                                  <span class='badge badge-danger'>Vote count <?php echo $data[$i]->vote_count ?></span>&nbsp

                                  </div>
                                  </div>
                                  </div>
                                  </div>
                                  </div>
                                  <br>
                                <?php }
                                $pageNumber++;
                            }while ($pageNumber <= $totalPages );
                        }
                    }
        ?>

    </div>
    
</body>

</html>
<?php

$movieUrl = "https://api.themoviedb.org/3/";


function fetch_curl($url){
    //create CURL resource
    $curl = curl_init();

    //set CURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Token token=8SlKDeXOrd8Kgn0hrvQg0gtt'
    ));

    //execute CURL
    $data = curl_exec($curl);
    //Close
    curl_close($curl);
    return json_decode($data); 
}

function get_quotes($title) {

    $quotesUrl = "http://movie-quotes-app.herokuapp.com/api/v1/quotes?";
    $search = str_replace(" ", "-", $quotesUrl . "movie=" . $title);
    $result = fetch_curl($search);

    return $result;
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
    <br>
    <div class="overlay">
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
      <source src="mp4/Sony-cam.mp4" type="video/mp4">
    </video>
    </div>
    <?php 
    

if ($_SERVER['REQUEST_METHOD']=="GET"){

    if(isset($_GET['movie_id']) &&  is_numeric($_GET['movie_id']) && $_GET['movie_id'] != ""){

        $id = $_GET['movie_id'];
        $api_key = "api_key=cc619552d30b59fc7ee1cc2334a37d56";
        
        //print_r($result);
        $targetUrl = $movieUrl . "movie/" . $id . "?" . $api_key;                  
        
        $result = fetch_curl($targetUrl);

        $cast = fetch_curl($movieUrl . "movie/" . $id ."/credits" . "?" . $api_key);

        $cast_names = $cast->cast;

        if ($result == "") {

            echo "Sorry, we couldn't find the searched movie.";
        } else {
            
?>




    <div class="container">
        <div class="card">
            <div class="row no-gutters">
                <div class="col-auto">
                    <img src='https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php echo $result->poster_path ?>' class='img-fluid' alt='poster'>
                </div>
                <div class="col-md">
                    <div class="row no-gutters">
                        <div class="card-block px-2">
                            <h4 class="card-title">Title: <?php echo $result->original_title?></h4>
                            <small class ='text-muted'>Vote average:</small>
                            <div class="progress" style="height:15px">
                            <div class="progress-bar bg-success" style="width:<?php echo ($result->vote_average)* 10?>%;height:15px"><?php echo $result->vote_average?></div>
                            </div>
                            <p><small class ='text-muted'>Genre(s): </small>
                            <?php 
                            $genres = $result->genres;
                            for ($i = 0; $i < count($genres); $i++) {
                                echo "<span class='badge badge-pill badge-secondary'>" . $genres[$i]->name . "</span>&nbsp";
                            }
                            ?></p>
                            <small class ='text-muted'>Original Language: </small><?php echo $result->original_language?>
                            <p><small class ='text-muted'>Overview: </small><br/><?php echo $result->overview?></p>


                            <p><small class ='text-muted'>Cast: </small><br/>
                                <ul>
                                <?php  
                                for ($i = 0; $i < count($cast_names); $i++) {
                                    echo "<li><a href='page4.php?cast_id=". $cast_names[$i]->id . "'>" . $cast_names[$i]->name. "</a> as " . $cast_names[$i]->character. "";
                                }?>
                            </ul></p>
                            <p><small class ='text-muted'>Movie Quotes: </small>
                                <?php 
                                $quotes = get_quotes($result->original_title)
                                ?>
                                <ul>
                                <?php for ($i = 0; $i < count($quotes); $i++) {?>
                                <li><?php echo $quotes[$i]->content ?>(Character: <?php echo $quotes[$i]->character->name ?>; Actor: <?php echo $quotes[$i]->actor->name ?>;)</li>
                                <?php } ?>
                                <ul>
                                </p>
                           

                        </div><br/>
                    </div>
                    <br><br><br>
                    <div class="row no-gutters mt-0 position-absolute fixed-bottom w-100">
                        <div class="card-footer w-100 text-muted bg-light">
                        <span class='badge badge-success'>Budget <?php echo "USD ".sprintf('%01.2f', $result->budget)?></span>&nbsp
                        <span class='badge badge-warning'>Revenue <?php echo "USD ". sprintf('%01.2f', $result->revenue)?></span>&nbsp
                        <span class='badge badge-primary'>Run Time <?php echo $result->runtime . "min"?></span>&nbsp
                        <span class='badge badge-secondary'>Release Date <?php echo $result->release_date?></span>&nbsp
                        </div>
                    </div>
                    <?php            
                      }
                    } else {
                        ?>
                                    <div class="container">
                                    <div class='alert alert-warning alert-dismissible fade show'>
                                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                    <strong>Sorry! </strong> No record was found.
                                    </div></div>
                                <?php
                                }
                            }?><br/>
                </div>
            </div>
        </div>








    
    


        
    
</body>

</html>
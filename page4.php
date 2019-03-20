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

    if (isset($_GET["movie_id"])) {
        $search = str_replace(" ", "-", $quotesUrl . "movie=" . $title);
    } else {
        $search = str_replace(" ", "-", $quotesUrl . "actor=" . $title);
    }
    
    $result = fetch_curl($search);

    return $result;
}


function gender($gender) {

    $gender_name = "";
    switch ($gender) {
        case 1 : $gender_name = "Female";
        break;
        case 2 : $gender_name = "Male";
        break;
        default : $gender_name = "No gender specified"; 
    }

    return $gender_name;
}

function age($birthday) {

 $date = new DateTime($birthday);
 $now = new DateTime();
 $interval = $now->diff($date);
 return $interval->y;
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
    <br/>
    <div class="overlay">
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
      <source src="mp4/Sony-cam.mp4" type="video/mp4">
    </video>
    </div>
    <?php 
    

if ($_SERVER['REQUEST_METHOD']=="GET"){

    if(isset($_GET['cast_id']) &&  is_numeric($_GET['cast_id']) && $_GET['cast_id'] != ""){

        $id = $_GET['cast_id'];
        $api_key = "api_key=cc619552d30b59fc7ee1cc2334a37d56";
        
        //print_r($result);
        $targetUrl = $movieUrl . "person/" . $id . "?" . $api_key;
            
        
        $result = fetch_curl($targetUrl);

        //$cast = fetch_curl($movieUrl . "movie/" . $id ."/credits" . "?" . $api_key);
        //$cast_names = $cast->cast;

        if (empty($result)) {

            echo "Sorry, we couldn't find the searched actor.";
        } else {
?>


    <div class="container">
        <div class="card">
            <div class="row no-gutters">
                <div class="col-auto">       
                    <?php
                                    if(isset($result->profile_path)){?>
                                        <img src='https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php  echo $result->profile_path  ?>' class='img-fluid' alt='poster'>
                                    <?php }else{ ?>
                                        <img src='nophoto.jpg' class='img-fluid' alt='poster'>
                                    <?php } ?>
                </div>
                <div class="col-md">
                    <div class="row no-gutters">
                        <div class="card-block px-2">
                            <h4 class="card-title"><?php echo $result->name ?></h4>
                            <p><small class ='text-muted'>Birthday: <?php echo $result->birthday ?></small>
                            <?php 
                            if ($result->deathday != "") {
                            ?></p>
                            <p><small class ="text-muted">Death Day: </small><?php echo $result->deathday ?></p>
                            <?php } ?>
                            <p ><small class ="text-muted">Age: </small><?php echo age($result->birthday)?></p>
                            <p ><small class ="text-muted">Place of Birth: </small><?php echo $result->place_of_birth ?></p>
                            <p ><small class ="text-muted">Gender: </small><?php echo gender($result->gender);?></p>
                            <p ><small class ="text-muted">Biography: </small><?php echo $result->biography;?></p>
                            <p ><small class ="text-muted">Homepage: </small><?php echo $result->homepage;?></p>
                            <p ><small class ="text-muted">Famous Quotes: </small></p>
                            <?php 
                            $quotes = get_quotes($result->name);
                            ?>
                            <ul>
                            <?php if(empty($quotes)) {?>
                            <li>No quotes for this actor.</li>
                            <?php } else {
                                for ($i = 0; $i < count($quotes); $i++) {
                            ?>
                            <li>
                            <?php 
                            echo $quotes[$i]->content?>
                            (Movie: <?php echo $quotes[$i]->movie->title ?>; Character: <?php echo $quotes[$i]->character->name?>;).</li>
                            <?php } }  ?>
                            </ul>
                                </p>
                        </div><br/>
                    </div>
                    <div class="row no-gutters mt-0 position-absolute fixed-bottom w-100">
                        </div>
                    </div>
                </div>
                <?php            
                        }
                        } else { ?>
                        <div class="container">
                        <div class='alert alert-warning alert-dismissible fade show'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <strong>Ops! </strong> invalid Request.
                        </div></div>
                        <?php
                    }}
                ?>
            </div>
        </div>

         
    
</body>

</html>

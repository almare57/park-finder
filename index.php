<?php
    include("curlRequest.php");

    $response = curlRequest("activities");
    if($response["data"] && !empty((array)$response["data"])){
        $activities = $response["data"];
    }
    else{
        $message = $response["message"];
    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>National Park Finder</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css" integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/c4d24787f5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>

    <header>
        <h1>Find National Parks By Activities</h1>
    </header>

    <section>
        <?php
            if(!empty($activities)){
        ?>
            <label for="select-activity">Select An Activity</label>
            <div class="select-wrapper">
                <select id="select-activity">
                    <option selected disabled value="">Select An Activity</option>
                    <?php
                        foreach($activities as $activity){
                            echo '<option value="'.$activity->id.'">'.$activity->name.'</option>';
                        }
                    ?>
                </select>
            </div>
        <?php
            }
            else{
                echo $message;
            }
        ?>
    </section>

    <main id="results">
    </main>
</body>

<script>
    $("#select-activity").change(function(event){
        let activity_id = $(this).val()
        if(activity_id && activity_id != ""){
            $.ajax({
                method: "GET",
                url: "/getParks.php",
                data: {activity_id: activity_id}, // Optional data to send
                success: function (response) {
                    $("#results").html("");
                    let obj = JSON.parse(response);
                    if(obj.data && obj.data[0]["parks"]){
                        let parks = obj.data[0]["parks"];

                        $.each(parks, function(index, park){
                            let new_park = '<article class="park"><h4>'+park.fullName+'</h4>';
                                new_park += '<ul><li>State(s): '+park.states+'</li>';
                                new_park += '<li>website: <a href="'+park.url+'" target="_blank">'+park.url+'</a></li></ul></article>';
                            $( "#results" ).append(new_park);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    $("#results").html("");
                    let obj = JSON.parse(xhr.responseText);
                    if(obj.message){
                        console.log(obj.message);
                    }
                }
            });
        }
    });
</script>

</html>
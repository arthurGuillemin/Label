<?php
require_once './database.php';

// recup les donnes
function fetchData($conn){
    $stmt = $conn->prepare("
        SELECT 
            Human.id AS human_id, Human.first_name, Human.last_name,
            Musician.birth_date, Musician.biography, Musician.instrument,
            Engineer.salary,
            `Group`.name AS group_name, `Group`.creation_date AS group_creation_date,
            Track.title AS track_title, Track.duration AS track_duration,
            Album.title AS album_title, Album.publication_date AS album_date,
            Concert.date AS concert_date, Concert.city AS concert_city,
            Session.recording_date, Session.studio
        FROM Human
        LEFT JOIN Musician ON Human.id = Musician.id
        LEFT JOIN Engineer ON Human.id = Engineer.id
        LEFT JOIN Group_Musician ON Musician.id = Group_Musician.musician_id
        LEFT JOIN `Group` ON Group_Musician.group_id = `Group`.id
        LEFT JOIN Musician_Track ON Musician.id = Musician_Track.musician_id
        LEFT JOIN Track ON Musician_Track.track_id = Track.id
        LEFT JOIN Album_Track ON Track.id = Album_Track.track_id
        LEFT JOIN Album ON Album_Track.album_id = Album.id
        LEFT JOIN Group_Concert ON `Group`.id = Group_Concert.group_id
        LEFT JOIN Concert ON Group_Concert.concert_id = Concert.id
        LEFT JOIN Musician_Session ON Musician.id = Musician_Session.musician_id
        LEFT JOIN Session ON Musician_Session.session_id = Session.id
        ORDER BY Human.id
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//  afficher la data
function displayData($results) {
    if ($results) {
        foreach ($results as $row) {
            echo "ID : " . $row['human_id'] . "<br>";
            echo "Prénom : " . $row['first_name'] . "<br>";
            echo "Nom : " . $row['last_name'] . "<br>";
            
            if ($row['birth_date']) {
                echo "Date de naissance : " . $row['birth_date'] . "<br>";
                echo "Biographie : " . $row['biography'] . "<br>";
                echo "Instrument : " . $row['instrument'] . "<br>";
            }
            
            if (isset($row['salary'])) {
                echo "Salaire : " . $row['salary'] . "<br>";
            }

            if (isset($row['group_name'])) {
                echo "Groupe : " . $row['group_name'] . "<br>";
                echo "Date de création du groupe : " . $row['group_creation_date'] . "<br>";
            }

            if (isset($row['track_title'])) {
                echo "Titre du morceau : " . $row['track_title'] . "<br>";
                echo "Durée : " . $row['track_duration'] . "<br>";
            }

            if (isset($row['album_title'])) {
                echo "Album : " . $row['album_title'] . "<br>";
                echo "Date de publication : " . $row['album_date'] . "<br>";
            }

            if (isset($row['concert_date'])) {
                echo "Date de concert : " . $row['concert_date'] . "<br>";
                echo "Ville du concert : " . $row['concert_city'] . "<br>";
            }

            if (isset($row['recording_date'])) {
                echo "Date de session : " . $row['recording_date'] . "<br>";
                echo "Studio : " . $row['studio'] . "<br>";
            }

            echo "<hr>";
        }
    } else {
        echo "Aucune donnée trouvée.";
    }
}

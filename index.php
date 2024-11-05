<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "music_db";

// Fonction pour établir la connexion à la base de données
function getDatabaseConnection($servername, $username, $password, $dbname) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}

// Fonction pour récupérer les données
function fetchData($conn) {
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

// Fonction pour afficher les données
function displayData($results) {
    if ($results) {
        foreach ($results as $row) {
            echo "ID : " . $row['human_id'] . "<br>";
            echo "Prénom : " . $row['first_name'] . "<br>";
            echo "Nom : " . $row['last_name'] . "<br>";
            
            // Musicien
            if ($row['birth_date']) {
                echo "Date de naissance : " . $row['birth_date'] . "<br>";
                echo "Biographie : " . $row['biography'] . "<br>";
                echo "Instrument : " . $row['instrument'] . "<br>";
            }
            
            // Ingénieur
            if (isset($row['salary'])) {
                echo "Salaire : " . $row['salary'] . "<br>";
            }

            // Groupe
            if (isset($row['group_name'])) {
                echo "Groupe : " . $row['group_name'] . "<br>";
                echo "Date de création du groupe : " . $row['group_creation_date'] . "<br>";
            }

            // Morceaux (Tracks)
            if (isset($row['track_title'])) {
                echo "Titre du morceau : " . $row['track_title'] . "<br>";
                echo "Durée : " . $row['track_duration'] . "<br>";
            }

            // Album
            if (isset($row['album_title'])) {
                echo "Album : " . $row['album_title'] . "<br>";
                echo "Date de publication : " . $row['album_date'] . "<br>";
            }

            // Concert
            if (isset($row['concert_date'])) {
                echo "Date de concert : " . $row['concert_date'] . "<br>";
                echo "Ville du concert : " . $row['concert_city'] . "<br>";
            }

            // Session d'enregistrement
            if (isset($row['recording_date'])) {
                echo "Date de session : " . $row['recording_date'] . "<br>";
                echo "Studio : " . $row['studio'] . "<br>";
            }

            echo "<hr>"; // Séparateur entre chaque enregistrement
        }
    } else {
        echo "Aucune donnée trouvée.";
    }
}

// Main execution
$conn = getDatabaseConnection($servername, $username, $password, $dbname);
if ($conn) {
    $results = fetchData($conn);
    displayData($results);
    $conn = null; // Fermeture de la connexion
}
?>

<?php
// includes/db.php - v4
// git commit: Färdig PDO-anslutning till Hostup (omega)

$host = 'omega.hostup.se';
$dbname = 'skydda_db_1';
$user = 'skyddauser';
$pass = 'r+}GM)t6TMgpM8TE>sJ-Z!v@:$#:Xw>5gA]&}YMq<ROx3.<k';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "✅ Ansluten till databasen '$dbname'";
} catch (PDOException $e) {
    die("❌ Fel vid databasanslutning: " . $e->getMessage());
}

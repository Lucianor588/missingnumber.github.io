<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loterias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$rss_feed_url = "https://enloteria.com/rss";
$rss_feed = simplexml_load_file($rss_feed_url);
if ($rss_feed === false) {
    die("Error al cargar el feed RSS.");
}

$numberOccurrences = array_fill(0, 100, 0);

foreach ($rss_feed->channel->item as $feed_item) {
    $title = (string) $feed_item->title;
    $hoyIndex = strpos($title, 'hoy:');
    if ($hoyIndex !== false) {
        $numbers_str = substr($title, $hoyIndex + 4);
        preg_match_all('/\b\d{2}\b/', $numbers_str, $matches);
        foreach ($matches[0] as $number) {
            $numberOccurrences[(int)$number]++;
        }
    }
}

// Verificar el array de ocurrencias antes de insertar en la base de datos
echo "<pre>";
print_r($numberOccurrences);
echo "</pre>";

$columns = implode(", ", array_map(function($num) { return "numero" . str_pad($num, 2, '0', STR_PAD_LEFT); }, range(0, 99)));
$values = implode(", ", array_values($numberOccurrences));
$sql = "INSERT INTO resultados ($columns) VALUES ($values)";

if ($conn->query($sql) === TRUE) {
    echo "Datos insertados correctamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
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

$sql = "SELECT * FROM resultados ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No se encontraron resultados en la base de datos.";
    $row = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de 10x20</title>
    <style>
        table { width: 100%; }
        th { background-color: #D0D3D4; color: #0000ff; text-align: center; }
        td { background-color: #ffffff; color: #000000; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <center>
        <table border="1" align="center">
            <?php for ($j = 0; $j < 10; $j++): ?>
                <thead>
                    <tr>
                        <?php for ($i = $j * 10; $i < ($j + 1) * 10; $i++): ?>
                            <th align="center"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php for ($i = $j * 10; $i < ($j + 1) * 10; $i++): ?>
                            <td id="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" align="center">
                                <?php 
                                $colName = 'numero' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                echo isset($row[$colName]) ? (int)$row[$colName] : '&nbsp;';
                                ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                </tbody>
            <?php endfor; ?>
        </table>
    </center>
</body>
</html>

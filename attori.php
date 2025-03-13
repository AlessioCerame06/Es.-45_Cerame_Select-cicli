<?php
    include("connessione/connessione.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $sql_count = "SELECT COUNT(*) AS totale_attori FROM Attori";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $totale_attori = $row_count["totale_attori"];
            $num_attori = $_GET["num_attori"];
            
            if ($num_attori > $totale_attori) {
                echo "<h3>Il numero fornito è maggiore rispetto al numero totale degli attori. Saranno mostrati solo $totale_attori attori</h3>";
                $num_attori = $totale_attori;
            }
            
            $sql_attori = "SELECT CodAttore, Nome FROM Attori ORDER BY Nome ASC LIMIT $num_attori";
            $result_attori = $conn->query($sql_attori);
            
            if ($result_attori->num_rows > 0) {
                while ($attore = $result_attori->fetch_assoc()) {
                    $cod_attore = $attore["CodAttore"];
                    $nome_attore = $attore["Nome"];
            
                    echo "<h2>ATTORE: $cod_attore - $nome_attore</h2>";
                    $sql_conta_film = "SELECT COUNT(*) AS num_film FROM Recita WHERE CodAttore = $cod_attore";
                    $result_conta_film = $conn->query($sql_conta_film);
                    $row_film = $result_conta_film->fetch_assoc();
                    $num_film = $row_film["num_film"];
            
                    echo "<p>Numero di film in cui ha recitato: $num_film</p>";
            
                    if ($num_film > 0) {
                        $sql_film = "SELECT f.CodFilm, f.Titolo, f.AnnoProduzione 
                                     FROM Film f 
                                     JOIN Recita r ON f.CodFilm = r.CodFilm 
                                     WHERE r.CodAttore = $cod_attore";
                        $result_film = $conn->query($sql_film);
            
                        echo "<ul>";
                        while ($film = $result_film->fetch_assoc()) {
                            echo "<li>{$film['CodFilm']} - {$film['Titolo']} ({$film['AnnoProduzione']})</li>";
                        }
                        echo "</ul>";
                    }
                }
            } else {
                echo "<p>Nessun attore trovato.</p>";
            }
        ?>
    </body>
</html>
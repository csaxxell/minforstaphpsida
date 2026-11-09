<?php

function saveFilm($namn, $beskrivning, $datum, $kategori, $pdo){
	try {
		$stmt = $pdo->prepare("INSERT INTO sedda_filmer(namn, beskrivning, datum, kategori)
								VALUES (:namn, :beskrivning, :datum, :kategori)");
		$stmt->bindValue(":namn", $namn, PDO::PARAM_STR);
		$stmt->bindValue(":beskrivning", $beskrivning, PDO::PARAM_STR);
		$stmt->bindValue(":datum", $datum, PDO::PARAM_STR);
		$stmt->bindValue(":kategori", $kategori, PDO::PARAM_STR);

		return $stmt->execute();
	}
	
	catch (PDOException $e) {
		//echo $e->getMessage();
		return false;
	}
}


function renderFilmsList($pdo){
	$films = $pdo->query("SELECT * FROM sedda_filmer
						ORDER BY datum DESC")
						->fetchAll();

	echo "<table class='border'>";
	echo "<tr>
			<th class='border'>_Id_</th>
			<th class='border'>Titel</th>
			<th class='border'>Beskrivning</th>
			<th class='border'>Datum</th>
			<th class='border'>Kategori</th>
			<th class='border'>-</th>
			<th class='border'>-</th>
		</tr>";
		
	foreach ($films as $row){
		echo
			"<tr>
			<td class='border text-center'>".$row['film_id']."</td>
			<td class='border'>".$row['namn']."</td>
			<td class='border text-center'>".$row['beskrivning']."</td>
			<td class='border'>".$row['datum']."</td>
			<td class='border text-center'>".$row['kategori']."</td>
			<td class='border'><a href='edit.php?id=" . $row['film_id'] . "'>Redigera</a></td>
			<td class='border'>
				<a href='?delete=true&id=" . $row['film_id'] . "' 
				onclick=\"return confirm('Är du säker på att du vill ta bort denna film?');\">Ta bort</a>
			</td>
			
			</tr>";
	}
	
	echo "</table>";
}

function getFilmById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM sedda_filmer WHERE film_id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    } 
	
	catch (PDOException $e) {
		
		//echo "Error: " . $e->getMessage();
		
        return null;
    }
}

function updateFilm($filmId, $namn, $beskrivning, $datum, $kategori, $pdo) {
    try {
        $sql = "UPDATE sedda_filmer SET namn = :namn, datum = :datum, kategori = :kategori, beskrivning = :beskrivning
                WHERE film_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'namn' => $namn,
            'datum' => $datum,
            'kategori' => $kategori,
            'beskrivning' => $beskrivning,
            'id' => $filmId
        ]);
        return true;
    } 
	catch (PDOException $e) {

        //echo "Error: " . $e->getMessage();
        return false;
    }
}


function deleteFilm($filmId, $pdo) {
	try {

		$stmt = $pdo->prepare("DELETE FROM sedda_filmer WHERE film_id = :id");
		$stmt->execute(['id' => $filmId]);
		return true;
    }
	
	catch (PDOException $e) {

        //echo "Error: " . $e->getMessage();
        return false;
    }
}



?>


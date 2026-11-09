
<?php
require_once "config.php";
require_once "functions.php";

$sql = "SHOW COLUMNS FROM sedda_filmer LIKE 'kategori'";
$result = $pdo->query($sql);
$row = $result->fetch();

preg_match_all("/'([^']*)'/", $row['Type'], $matches);
$kategorier = $matches[1];


//$filmId = $_GET['film_id'] ?? null;

//var_dump($filmId);


if (isset($_POST['submit-film'])) {
    if (saveFilm(
        $_POST["namn"],
        $_POST["beskrivning"],
        $_POST["datum"],
        $_POST["kategori"],
        $pdo
    )) {
        $feedbackmessage = [1, " Film skapad!"];
    }
    else {
        $feedbackmessage = [0, " Ett fel har inträffat!"];
    }
}


if (isset($_GET['delete']) && $_GET['delete'] === 'true' && isset($_GET['id'])) {
	$filmId = $_GET['id'] ?? null;
	
    if (deleteFilm($filmId, $pdo)) {
        $feedbackmessage = [1, "Filmen har tagits bort!"];
    } else {
        $feedbackmessage = [0, "Något gick fel. Filmen kunde inte tas bort."];
    }
	
}


if (isset($feedbackmessage) && $feedbackmessage[0] === 1) {
	
	echo "<strong>Lyckades!</strong>";
	echo htmlspecialchars($feedbackmessage[1]);
}

if (isset($feedbackmessage) && $feedbackmessage[0] === 0) {
	
	echo "<strong>Error!</strong>";
	echo htmlspecialchars($feedbackmessage[1]);
}

?>

<!-- Testar GIT -->

<!doctype html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
  <title>Filmer</title>

</head>
<body>


  <h1>Filmer jag sett</h1>
  <p>Fyll i uppgifterna nedan och skicka.</p>

	<form id="filmForm" action="/filmer/" method="post">
	
		<label for="namn">Namn</label>
		<input id="namn" name="namn" type="text" maxlength="255" required placeholder="Namn">

		<label for="beskrivning">Beskrivning</label>
		<input id="beskrivning" name="beskrivning" type="text" maxlength="255" required placeholder="Beskrivning"><br>

		<label for="datum">Datum</label>
		<!--<input id="datum" name="datum" type="text" maxlength="255" required placeholder="YYYY-MM-DD">-->
		<input type="date" name="datum" value="<?= date('Y-m-d') ?>">		

		<label for="kategori">Kategori</label>
		<!--<input id="kategori" name="kategori" type="text" maxlength="255" required placeholder="kategori">-->
		<select name="kategori" id="kategori">
			<?php foreach ($kategorier as $kategori): ?>
				<option value="<?= htmlspecialchars($kategori) ?>">
				<?= htmlspecialchars($kategori) ?>
				</option>
			<?php endforeach; ?>
		</select>


		<div class="actions">
		  <input type="submit" value="Skicka" name="submit-film">
		</div>
	
	</form>
	
	<div class="m-t">
		<?php
			$allFilms = renderFilmsList($pdo);
		?>
	</div>
 
</body>
</html>
<?php

require_once "config.php";
require_once "functions.php";

$sql = "SHOW COLUMNS FROM sedda_filmer LIKE 'kategori'";
$result = $pdo->query($sql);
$row = $result->fetch();

preg_match_all("/'([^']*)'/", $row['Type'], $matches);
$kategorier = $matches[1];


$filmId = $_GET['id'] ?? null;


if (isset($_POST['update-film'])) {
    if (updateFilm(
		$filmId,
        $_POST["namn"],
        $_POST["beskrivning"],
        $_POST["datum"],
        $_POST["kategori"],
        $pdo
    )) {
        $feedbackmessage = [1, " Film uppdaterad!"];
    }
    else {
        $feedbackmessage = [0, " Ett fel har inträffat!"];
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

$filmData = getFilmById($pdo, $filmId);
//print_r ($filmData);
//var_dump($filmId, $filmData);

?>




<h1>Redigera film</h1>
<form id="filmForm" action="" method="post">
	
		<label for="namn">Namn</label>
		<input id="namn" name="namn" type="text" maxlength="255" required 
		value="<?php echo isset($filmData['namn']) ? htmlspecialchars($filmData['namn']) : ''; ?>">

		<label for="beskrivning">Beskrivning</label>
		<textarea id="beskrivning" name="beskrivning" maxlength="255" required><?php echo isset($filmData['beskrivning']) ? 
		htmlspecialchars($filmData['beskrivning']) : ''; ?></textarea><br>

		<label for="datum">Datum</label>
		<!--<input id="datum" name="datum" type="text" maxlength="255" required placeholder="YYYY-MM-DD">-->
		<input type="date" name="datum" 
		value="<?php echo isset($filmData['datum']) ? htmlspecialchars($filmData['datum']) : ''; ?>">		

		<label for="kategori">Kategori</label>
		<!--<input id="kategori" name="kategori" type="text" maxlength="255" required placeholder="kategori">-->
		<select name="kategori" id="kategori">
			<?php foreach ($kategorier as $kat): ?>
			
				<option 
					value="<?= htmlspecialchars($kat) ?>"
					<?= $kat === ($filmData['kategori'] ?? '') ? 'selected' : '' ?>
				>
				<?= htmlspecialchars($kat) ?>
				</option>
				
			<?php endforeach; ?>
		</select>


		<div class="actions">
		  <input type="submit" value="Skicka" name="update-film">
		</div>
	
	</form>
	
	
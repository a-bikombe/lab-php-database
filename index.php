<?php

/* Enable error reporting */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/* Connect to the database */
$mysqli = mysqli_connect("127.0.0.1", "root", "", "csci206", 3306);

/* Set the desired charset after establishing a connection */
mysqli_set_charset($mysqli, 'utf8mb4');

/* Create a prepared statement */
$stmt = mysqli_prepare($mysqli, "SELECT * FROM php WHERE del = 0 || del is null");

/* bind parameters for markers */
//mysqli_stmt_bind_param($stmt, "s", $city);

/* execute query */
mysqli_stmt_execute($stmt);

/* Read each row of the results as an associative array */
$result = mysqli_stmt_get_result($stmt);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
	$rows[] = $row;
}

/* Free all the resources */
/* mysqli_free_result($result);
mysqli_stmt_close($stmt);
mysqli_close($mysqli); */

?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>PHP</title>
	<link href="main.css" rel="stylesheet">
	<script defer src="script.js"></script>
</head>

<body>
	<h1>Database</h1>
	<form action="validation.php" method="post">
		<table>
			<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Message</th>
					<th>Created</th>
					<th>Modified</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rows as $v) :  ?>
					<tr>
						<td><?= $v['id'] ?></td>
						<td>
							<input type="text" name="<?= $v['id'] ?>-name" value="<?= htmlentities($v['name']) ?>">
						</td>
						<td>
							<textarea name="<?= $v['id'] ?>-message"><?= htmlentities($v['message']) ?></textarea>
						</td>
						<td><?= $v['created'] ?></td>
						<td><?= $v['modified'] ?></td>
						<td><input type="checkbox" name="<?= $v['id'] ?>-del"></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<template id="tr">
			<tr>
				<td></td>
				<td>
					<input type="text">
				</td>
				<td>
					<textarea></textarea>
				</td>
				<td></td>
				<td></td>
			</tr>
		</template>
		<button id="add-row" type="button">Add</button>
		<button type="submit">Submit</button>
		<button type="reset">Reset</button>
	</form>
</body>

</html>
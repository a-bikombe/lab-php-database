<?php

ini_set('display_errors', 1);

if (empty($_POST)) {
	die;
}

$data = [];
$new_data = [];
foreach ($_POST as $k => $v) {
	// split the id and name
	$row_data = explode('-', $k);
	if (!is_numeric($row_data[0])) {
		die;
	}
	if ($row_data[1] === 'new') {
		$new_data[$row_data[0]][$row_data[2]] = $v;
	} else {
		// use the id and the name of the data to identify the value
		$data[$row_data[0]][$row_data[1]] = $v;
	}
}

function validate($data)
{
	$args = [
		'name' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[\w, .\'-]{2,64}$/']],
		'message' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[\w\s-]{5,256}$/']],
		'del' => FILTER_VALIDATE_BOOLEAN
	];
	$results = [];
	foreach ($data as $id => $input) {
		$results[$id] = filter_var_array($input, $args);
		if (empty($results) || in_array(false, $results, true)) {
			var_dump($input, $results);
			die;
		}
	}
	return $results;
}

$update_data = validate($data);
$insert_data = validate($new_data);

?>
<pre><?= var_dump($update_data); ?></pre>
<?php

$mysqli = mysqli_connect("127.0.0.1", "root", "", "csci206", 3306);

/* Set the desired charset after establishing a connection */
mysqli_set_charset($mysqli, 'utf8mb4');

foreach ($update_data as $id => $input) {

	/* Create a prepared statement */
	$stmt = mysqli_prepare($mysqli, "UPDATE php SET name = ?, message = ?, del = ? WHERE id = ?");

	/* bind parameters for markers */
	mysqli_stmt_bind_param($stmt, "ssii", $input['name'], $input['message'], $input['del'], $id);

	/* execute query */
	if (!mysqli_stmt_execute($stmt)) {
		echo mysqli_error($mysqli).PHP_EOL;
		die('update failed');
	}
}

foreach ($insert_data as $id => $input) {

	/* Create a prepared statement */
	$stmt = mysqli_prepare($mysqli, "INSERT INTO php(name,message) VALUES (?,?)");

	/* bind parameters for markers */
	mysqli_stmt_bind_param($stmt, "ss", $input['name'], $input['message']);

	/* execute query */
	if (!mysqli_stmt_execute($stmt)) {
		die('insert failed');
	}
}

/* Free all the resources */
mysqli_stmt_close($stmt);
mysqli_close($mysqli);
header('Location: index.php');
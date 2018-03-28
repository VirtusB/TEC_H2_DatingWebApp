<?php
//$dbh = mysqli_connect('127.0.0.1', 'virtusbc_h2_user', 'rootpwdating', 'virtusbc_tec-dating');

$dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

try {

// Find out how many items are in the table
$total = $dbh->query('
    SELECT
        COUNT(*)
    FROM
        Users
')->fetchColumn();

// How many items to list per page
$limit = 1;

// How many pages will there be
$pages = ceil($total / $limit);

// What page are we currently on?
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => 1,
        'min_range' => 1,
    ),
)));

// Calculate the offset for the query
$offset = ($page - 1)  * $limit;

// Some information to display to the user
$start = $offset + 1;
$end = min(($offset + $limit), $total);

// The "back" link
$prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

// The "forward" link
$nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

// Display the paging information
echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

// Prepare the paged query
$stmt = $dbh->prepare('
    SELECT
        *
    FROM
        Users
    ORDER BY
        name
    LIMIT
        :limit
    OFFSET
        :offset
');

// Bind the query params
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Do we have any results?
if ($stmt->rowCount() > 0) {
    // Define how we want to fetch the results
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $iterator = new IteratorIterator($stmt);

    // Display the results
    foreach ($iterator as $row) {
        echo '<p>', $row['name'], '</p>';
        echo '<p>', $row['joined'], '</p>';
        echo '<p>', $row['countryId'], '</p>';
        echo '<p>', $row['regionId'], '</p>';
        echo '<p>', $row['city'], '</p>';
        echo '<p>', $row['sex'], '</p>';
        echo '<p>', $row['age'], '</p>';
        echo '<p>', $row['profileBio'], '</p>';
        echo '<img src="data:image/gif;base64,' . $row['imageFile'] . '" />';
    }

} else {
    echo '<p>No results could be displayed.</p>';
}

} catch (Exception $e) {
echo '<p>', $e->getMessage(), '</p>';
}
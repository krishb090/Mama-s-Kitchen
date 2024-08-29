<?php
// Include your database connection file
include "connect.php"; // Adjust the path as necessary

// Initialize search query variable
$search_query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

// Prepare the SQL query to match if the first two letters of the menu_name match the search query
$sql = "SELECT * FROM menus WHERE menu_name LIKE :search_query";
$stmt = $con->prepare($sql);
$stmt->execute(['search_query' => '%' . $search_query . '%']);
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Link to your existing CSS -->
</head>
<body>
    <header>
        <!-- Your header content (e.g., navigation bar) -->
    </header>

    <main>
        <section id="search-results">
            <h1 style="text-align: center;">Search Results</h1>
            <!-- Display the results -->
            <?php if ($menu_items): ?>
                <ul class="menu-items">
                    <?php foreach ($menu_items as $item): ?>
                        <li>
                            <img src="assets/img/<?php echo htmlspecialchars($item['menu_image']); ?>" alt="<?php echo htmlspecialchars($item['menu_name']); ?>" />
                            <h3><?php echo htmlspecialchars($item['menu_name']); ?></h3>
                            <p><?php echo htmlspecialchars($item['menu_description']); ?></p>
                            <p>Price: $<?php echo number_format($item['menu_price'], 2); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No menu items found.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <!-- Your footer content -->
    </footer>
</body>
</html>
<style>
    /* General styles for the entire website */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
}

header, footer {
    background-color: #f8f8f8;
    padding: 20px;
    text-align: center;
}

main {
    padding: 20px;
}

/* Styles for search results section */
#search-results {
    max-width: 1200px;
    margin: 0 auto;
}

#search-results h1 {
    font-size: 2em;
    margin-bottom: 20px;
    color: #333;
}

ul.menu-items {
    list-style-type: none;
    padding: 0;
}

ul.menu-items li {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 20px auto;
}

ul.menu-items li img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
}

ul.menu-items li h3 {
    margin: 10px 0;
    color: #333;
}

ul.menu-items li p {
    margin: 5px 0;
    color: #666;
}

/* Ensure the search form is also styled consistently if included */
#search-form {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

#search-form input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px;
}

#search-form button {
    padding: 10px 20px;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

#search-form button:hover {
    background-color: #0056b3;
}
.navbar 
{
    border: medium none;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    -ms-border-radius: 0;
    border-radius: 0;
    margin: 0;
    position: relative;
    padding: 0 !important;
}

.header-section 
{
    transition: all .3s ease-in-out;
}

.header-section
{
    background-color:#222227;
    width:100%;
    height:120px;
    z-index:999;
    position:fixed;
    left:0;
    top:0;
    padding:0;
    display:flex;
    align-items:center
}

.menu-wrap 
{
    position: relative;
}

ul.nav>li 
{
    position: relative;
}

ul.nav>li>a 
{
    color: #ddd;
    font-family: work sans,sans-serif;
    display: inline-block;
    vertical-align: middle;
    padding: 0 20px;
    letter-spacing: 0;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    line-height: 80px;
    z-index: 1;
    transition: 0.5s;

}

.header-btn .menu-btn 
{ 
    background-color: #ffc851; 
    font-family: work sans,sans-serif; 
    font-size: 12px; 
    text-transform: uppercase; 
    color: #fff; 
    padding: 0 30px; 
    height: 45px; 
    line-height: 45px; 
    display: block; 
    margin: 0; 
}

.header-btn .menu-btn:hover 
{
    opacity: .8;
}

ul.nav>li>a:hover
{
    color: #ffc851;
}
</style>
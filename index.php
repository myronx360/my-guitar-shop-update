<?php
require_once('database.php');

// Get category ID
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
if ($category_id == NULL || $category_id == FALSE) {
    $category_id = 1;
}
    
// Get name for selected category
$queryCategory = 'SELECT * FROM categories
                      WHERE categoryID = :category_id';
$statement1 = $db->prepare($queryCategory);
$statement1->bindValue(':category_id', $category_id);
$statement1->execute();
$category = $statement1->fetch();
$category_name = $category['categoryName'];
$statement1->closeCursor();

// Get all categories
$queryAllCategories = 'SELECT * FROM categories
                           ORDER BY categoryID';
$statement2 = $db->prepare($queryAllCategories);
$statement2->execute();
$categories = $statement2->fetchAll();
$statement2->closeCursor();

// Get products for selected category
$queryProducts = 'SELECT * FROM Instruments
              WHERE categoryID = :category_id
              ORDER BY instrumentID';
$statement3 = $db->prepare($queryProducts);
$statement3->bindValue(':category_id', $category_id);
$statement3->execute();
$products = $statement3->fetchAll();
$statement3->closeCursor();
if(isset($_POST['rowID'])) {
    $InstrumentID  = $_POST['rowID'];
    $updatedCatID = $_POST['category'];
    $updatedName = $_POST["instName"];
    $updatedPrice = $_POST["instPrice"];
    echo $updatedCatID . ", " . $updatedName . ", " . $updatedPrice. " Updated";

    $query = "UPDATE instruments 
              SET categoryId = :upCategoryId, instrumentName = :upInstrumentName, listPrice = :upListPrice  
              WHERE InstrumentID = :InstrumentID";

    $statement = $db->prepare($query);
    $statement->bindValue(':InstrumentID', $InstrumentID);
    $statement->bindValue(':upCategoryId', $updatedCatID);
    $statement->bindValue(':upInstrumentName', $updatedName);
    $statement->bindValue(':upListPrice', $updatedPrice);
    $statement->execute();
    header("location:index.php");
        echo "records UPDATED successfully";

}
?>
<!DOCTYPE html>
<html>
<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- the body section -->
<body>
<?php

    ?>
<main>
    <h1>Instruments List</h1>
    <aside>
        <!-- display a list of categories -->
        <h2>Categories</h2>
        <nav>
        <ul>
            <?php foreach ($categories as $category) : ?>
            <li>
                <a href="?category_id=<?php echo $category['categoryID']; ?>">
                    <?php echo $category['categoryName']; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        </nav>           
    </aside>

    <section>
        <!-- display a table of products -->
        <h2><?php echo $category_name; ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th class="right">Price</th>
            </tr>

            <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo $product['InstrumentID']; ?></td>
                <td><?php echo $product['instrumentName']; ?></td>
                <td class="right"><?php echo $product['listPrice']; ?></td>
            </tr>
            <?php endforeach; ?>            
        </table>
        <form action='update_instruments_form.php' method='post'>
        <label>Enter Row ID : <input name='rowID' type='text'></label><br/>
        <input type='submit' value='Update Record' />
                <input type='reset' value='Reset' />
        </form>
    </section>
</main>    
<footer></footer>
</body>
</html>
<?php
require_once('database.php');
//Get row ID from previous form
$instrumentID = $_POST['rowID'];
echo $instrumentID;


//Select the information of the selected instrument
 $stmt = $db->prepare("SELECT * FROM Instruments where instrumentID = :instrID"); 
 $stmt->bindParam(':instrID', $instrumentID);
$stmt->execute();
$insrumentResult = $stmt->fetch();

//Get all categories
$queryAllCategories = 'SELECT * FROM categories
                           ORDER BY categoryID';
$statement2 = $db->prepare($queryAllCategories);
$statement2->execute();
$categories = $statement2->fetchAll();
?>
<html>
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
<body>
<form action='index.php' method='post'>
<label>Category: <select name='category'>
    <?php 
    foreach ($categories as $row){
    if($row['categoryID']==$insrumentResult['categoryID'])
        echo "<option selected='selected' value=". $row['categoryID'].">".$row['categoryName']."</option>";
        else	
        echo "<option value=". $row['categoryID'].">".$row['categoryName']."</option>";
    }
    ?>
</select></label>
        <br>
<label> Instrument Name :<input name='instName' type='text' value=<?php echo $insrumentResult['instrumentName']?>></label><br>
<label> Instrument Price :<input name='instPrice' type='text' value=<?php echo $insrumentResult['listPrice']?>></label><br>
<input type='hidden' value=<?php echo $instrumentID?> name='rowID' >
<input type='submit' value='Update'><input type='reset' value='Cancel'>

</form>
</body>
</html>
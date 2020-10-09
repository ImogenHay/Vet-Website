
<!-- http://localhost/VetWebsite.php -->

<?php
$conn = mysqli_connect("localhost", "root", "", "vet_db"); //connects to mysql database
if (! $conn){ //incase of error accessing mysql database
	die("Connection Failied: ". mysqli_connect_error());
}
?>






<!-- ####################### -->
<!-- CREATES WEB PAGE LAYOUT -->
<!-- ####################### -->

<!DOCTYPE HTML>
<html>
<title> Vets Database </title>

<head>
<h1> Veterinary Centres </h1>
<link rel="stylesheet" href="main.css" type="text/css">
<a href="https://www.portlandvets.co.uk/"><img src="cat.jpg" height="150" title="One of our Patients" alt="Patient Picture" /></a>
</head>

<body>
<p id="text"> 







<!-- ######################################## -->
<!-- DISPLAYS COMPANY AND BUILDING INFOMATION -->
<!-- ######################################## -->

<?php
$result = mysqli_query($conn, //querys mysql database
"SELECT Company.Name, Building.City, Building.Postcode, Building.BuildingID
FROM Building
INNER JOIN Company ON Building.Company=Company.CompanyID"); //selects records with matching values in both tables
echo "<h2>Practices:</h2><br>"; //displays heading
if(mysqli_num_rows($result) > 0){ //only does this if data exists to prevent errors
	while ($r = mysqli_fetch_assoc($result)) { //repeats for each selected record
		echo "<tr><strong><td>",$r["Name"],"</td><td> "; //reads data out of result of mysql query
		echo $r["City"],"</td></strong><td>  ";
		echo $r["Postcode"],"</td><td> Building:";
		echo $r["BuildingID"],"</td><br></tr>";
	}
} else {
	echo "0 results";
}
mysqli_free_result($result); //frees result of mysql query so more queries can be carried out
echo "<br><br><br>";










// ############## //
// DISPLAYS STAFF //
// ############## //

$result = mysqli_query($conn, //querys mysql database
"SELECT Name, Email, Building
FROM Staff 
ORDER BY Name ASC"); //displays slected records in alphabetical order of name 
echo "<h2>Staff:</h2><br>";
if(mysqli_num_rows($result) > 0){ //only does this if data exists to prevent errors
	while ($r = mysqli_fetch_assoc($result)) { //repeats for each selected record
		echo "<tr><strong><td>",$r["Name"],"</td></strong><td>   "; //reads data out of result of mysql query
		echo $r["Email"],"</td><td>   Building:";
		echo $r["Building"],"</td><br></tr>";
	}
} else {
	echo "0 results";
}
mysqli_free_result($result); //frees result of mysql query so more queries can be carried out
echo "<br><br><br>";








// ##################### //
// DISPLAYS APPOINTMENTS //
// ##################### //
$result = mysqli_query($conn, //querys mysql database
"SELECT Appointment.Time, Appointment.Room, Staff.Building, Staff.Name, Animal.Petname
FROM Appointment
INNER JOIN Vet ON Appointment.Vet=Vet.VetID
INNER JOIN Staff ON Appointment.Vet=Staff.StaffID
INNER JOIN Animal ON Appointment.Animal=Animal.AnimalID 
ORDER BY Time ASC LIMIT 5"); //only displays next 5 appointments
echo "<h2>Appointments:</h2>";
if(mysqli_num_rows($result) > 0){ //only does this if data exists to prevent errors
	echo "<table><tr><th>Time</th><th>Room</th><th>Building</th><th>Vet</th><th>Pet</th></tr>"; //table to display data
	while ($r = mysqli_fetch_assoc($result)) { //repeats for each selected record
		echo "<tr><td>",$r["Time"],"</td><td>  "; //reads data out of result of mysql query
		echo $r["Room"],"</td><td>  ";
		echo $r["Building"],"</td><td>";
		echo $r["Name"],"</td><td>  ";
		echo $r["Petname"],"</td><br></tr>";
	}
	echo "</table>";
} else {
	echo "0 results";
}
mysqli_free_result($result); //frees result of mysql query so more queries can be carried out
echo "<br><br><br>";

?>
</p>






<!-- ################################################### -->
<!-- DISPLAYS OWNERS PETS BASED ON SEARCH USING TEXT BOX -->
<!-- ################################################### -->

<h2>Pet Search:</h2>
<form id="owners" method="post" onsubmit="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" > 
<!-- $_SERVER["PHP_SELF"] is superglobal returing file name of current script so error messages display on this page -->
<!-- htmlspecialchars prevents attackers from expoiting code by injecting HTML or Javascript by converting characters -->
Enter Owner Name: <input type="text" name="owner"><br> 
<input type="submit" value="Submit"> 
</form> 

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){ //checks if form submitted (validaion)
	if (empty($_POST["owner"])){ //checks if text box empty (validation)
		echo "<br>Name is required.";
	} else {
		 $result = mysqli_query($conn, //querys mysql database
		"SELECT Owner.Name, Animal.Petname
		FROM Owner_Animal
		INNER JOIN Owner ON Owner_Animal.Owner=Owner.OwnerID
		INNER JOIN Animal ON Owner_Animal.Animal=Animal.AnimalID
		WHERE Owner.Name LIKE '".$_POST["owner"]."%'"); //where name starts with entered value so don't need to enter fullname
		echo "<h3>Pets:</h3>";
		if(mysqli_num_rows($result) > 0){ //only does this if data exists to prevent errors
			while ($r = mysqli_fetch_assoc($result)) { //repeats for each selected record
				echo "<tr><td>",$r["Name"],"</td><td> Owns "; //reads data out of result of mysql query
				echo $r["Petname"],"</td><br></tr>";
			}
		} else {
			echo "0 results";
		}
		mysqli_free_result($result); //frees result of mysql query so more queries can be carried out
	}
}
echo "<br><br><br>";
 ?>
 
 
 
 
 
 
<!-- ################################################# -->
<!-- DISPLAYS ANIMALS BASED ON INPUT FROM DROPDOWN BOX -->
<!-- ################################################# -->

<h2>Patients:</h2>
<form id="patients" method="get" onsubmit="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" > 
<!-- $_SERVER["PHP_SELF"] is superglobal returing file name of current script so error messages display on this page -->
<!-- htmlspecialchars prevents attackers from expoiting code by injecting HTML or Javascript by converting characters -->
Select Pet Type: <select name="patient"> <!-- drop down box -->
	<option value="cat">Cat</option>
	<option value="dog">Dog</option>
	<option value="other">Other</option>
<input type="submit" value="Submit"> 
</form> 

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET"){ //checks if form submitted (validaion)
	if ($_GET["patient"] == "cat"){ //if cat selected displays all cats
		$result = mysqli_query($conn, 
		"SELECT Animal.Petname, Animal.Gender, Animal.DOB, Cat.Colour
		FROM Animal
		INNER JOIN Cat ON Animal.AnimalID=Cat.CatID
		ORDER BY DOB DESC"); //ordered by DOB in descending order
		echo "<h2>Cats:</h2>";
		if(mysqli_num_rows($result) > 0){
			while ($r = mysqli_fetch_assoc($result)) {
				echo "<tr><td>",$r["Petname"],"</td>  <td>";
				echo "<td>",$r["Gender"],"</td>  <td>";
				echo "<td>",$r["DOB"],"</td>  <td>";
				echo $r["Colour"],"</td><br></tr>";
			}
		} else {
			echo "0 results";
		}
	} elseif ($_GET["patient"] == "dog"){ //else if dog selected displays all dogs
		$result = mysqli_query($conn, 
		"SELECT Animal.Petname, Animal.Gender, Animal.DOB, Dog.Breed
		FROM Animal
		INNER JOIN Dog ON Animal.AnimalID=Dog.DogID
		ORDER BY DOB DESC"); //ordered by DOB in descending order
		echo "<h2>Dogs:</h2>";
		if(mysqli_num_rows($result) > 0){
			while ($r = mysqli_fetch_assoc($result)) {
				echo "<tr><td>",$r["Petname"],"</td>  <td>";
				echo "<td>",$r["Gender"],"</td>  <td>";
				echo "<td>",$r["DOB"],"</td>  <td>";
				echo $r["Breed"],"</td><br></tr>";
			}
		} else {
			echo "0 results";
		}
	} else { //else means other was selected so displays animals that are not cats or dogs
		$result = mysqli_query($conn, 
		"SELECT Petname, Gender, DOB
		FROM Animal
		WHERE AnimalID NOT IN ((SELECT CatID FROM Cat) UNION (SELECT DogID FROM Dog))
		ORDER BY DOB DESC"); //uses subquery and union to select all animals not in Cat or Dog tables
		echo "<h2>Other Animals:</h2>";
		if(mysqli_num_rows($result) > 0){
			while ($r = mysqli_fetch_assoc($result)) {
				echo "<tr><td>",$r["Petname"],"</td>  <td>";
				echo "<td>",$r["Gender"],"</td>  <td>";
				echo "<td>",$r["DOB"],"</td>  <td></tr>";
			}
		} else {
			echo "0 results";
		}
	}
	mysqli_free_result($result); //frees result of mysql query so more queries can be carried out
}
echo "<br><br><br>";
 ?>
</body>
</html>






<!-- ##################################### -->
<!-- RELEASES CONNECTION TO MYSQL DATABASE -->
<!-- ##################################### -->
<?php
mysqli_close($conn);
?>


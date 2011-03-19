<?php
// database.php
// Code by Pauli Suuraho 2011
// Tietokantayhteysluokka. Sisältää kaikki tietokannan kyselysuoritukset, sekä tietokannan ylläpitorutiinit.


class Database
{
	public $yhteys;
	
	public function Connect() 
	{ 
	global $configs;
	// yhteyden muodostus tietokantaan
	try {
		$user= $configs['username'];
		$passwd = $configs['password'];
		$dbname = $configs['database'];
		$this->yhteys = new PDO("pgsql:host=localhost;dbname=$dbname",
						"$user", "$passwd");
	} catch (PDOException $e) {
		die("VIRHE: " . $e->getMessage());
	}		
	$this->yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function Query($sql)
	{
		// kyselyn suoritus     
		try {
			$kysely = $this->yhteys->prepare($sql);
			$kysely->execute();
			return $kysely;
		} catch (PDOException $e) {
			die("VIRHE: " . $e->getMessage());
		}
	}
	
	public function CreateTable($name,$sql)
	{
		// Luo taulun $name   
		try {
			$kysely = $this->yhteys->prepare("DROP TABLE IF EXISTS $name;");
			$kysely->execute();  

			$kysely = $this->yhteys->prepare("CREATE TABLE $name $sql");  
			$kysely->execute();  
			return $kysely;
		} catch (PDOException $e) {
			die("VIRHE: " . $e->getMessage());
		}
	}	

	public function AddUser($name,$password)
	{
		// Luo käyttäjän user   
		try {
			$kysely = $this->yhteys->prepare("INSERT INTO USERS (name, password) VALUES(?, ?);");
			$kysely->execute(array($name,$password));  

			return $kysely;
		} catch (PDOException $e) {
			die("VIRHE: " . $e->getMessage());
		}
	}	

	public function PrintTable($result)
	{
	// haettujen rivien tulostus

		echo "<table border=1>";
		$rivi = $result->fetch(PDO::FETCH_ASSOC);
		foreach ((array_keys($rivi)) as $sarake)
		{
			echo "<th>" . $sarake . "</th>";
		};
		echo "<tr>";
		foreach ($rivi as $sarake)
		{
			echo "<td>" . $sarake . "</td>";
		}
		echo "</tr>";

			while ($rivi = $result->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>";
			foreach ($rivi as $sarake)
			{
			echo "<td>" . $sarake . "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	
	public function Disconnect()
	{
		$this->yhteys = null;
	}
}

?>
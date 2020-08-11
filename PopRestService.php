<?php
    require "dbinfo.php";
    require "RestService.php";
    require "PopInfo.php";

//
// There is limited error handling in this code in order to keep the code as simple as
// possible.
 
class PopRestService extends RestService 
{
	private $info;
    
	public function __construct() 
	{
		// Passing in the string 'information' to the base constructor ensures that
		// all calls are matched to be sure they are in the form http://server/info/x/y/z 
		parent::__construct("info");
	}

	public function performGet($url, $parameters, $requestBody, $accept) 
	{
		switch (count($parameters))
		{
			case 1:
				// Note that we need to specify that we are sending JSON back or
				// the default will be used (which is text/html).
				header('Content-Type: application/json; charset=utf-8');
				// This header is needed to stop IE cacheing the results of the GET	
				header('no-cache,no-store');
				$this->getAllInfo();
				echo json_encode($this->info);
				break;

			case 2:
				$id = $parameters[1];
				
					$popinfo = $this->getInfoById($id);
					if ($popinfo != null)
					{
						header('Content-Type: application/json; charset=utf-8');
						header('no-cache,no-store');
						echo json_encode($popinfo);
					} else {
						$this->notFoundResponse();
					}
				
				break;

			case 3:
				$gender = $parameters[1];
				$age = $parameters[2];
				//Checks which gender has been put into the Url and accesses the appropriate function to retrieve correct data
				if($gender == "Males")
				{
					header('Content-Type: application/json; charset=utf-8');
					header('no-cache,no-store');
					$this->getMalesInfo($age);
					echo json_encode($this->info);
				} 
				else if($gender == "Females")
				{
					header('Content-Type: application/json; charset=utf-8');
					header('no-cache,no-store');
					$this->getFemalesInfo($age);
					echo json_encode($this->info);
				}
				else if($gender == "AllPersons")
				{
					header('Content-Type: application/json; charset=utf-8');
					header('no-cache,no-store');
					$this->getAllPersonsInfo($age);
					echo json_encode($this->info);
				}
				else 
				{
					$this->notFoundResponse();
				}
				break;
				
			default:	
				$this->methodNotAllowedResponse();
		}
	}

	public function performPost($url, $parameters, $requestBody, $accept) 
	{
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;

		$newInfo = $this->extractInfoFromJSON($requestBody);
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$sql = "insert into info (Geo_name, Geo_code, Year, Gender, Age, Population_Estimate) values (?, ?, ?, ?, ?, ?)";
			// We pull the fields of the book into local variables since 
			// the parameters to bind_param are passed by reference.
			$statement = $connection->prepare($sql);
			$geoname = $newInfo->getGeoName();
			$geocode = $newInfo->getGeoCode();
			$year = $newInfo->getYear();
			$gender = $newInfo->getGender();
			$age = $newInfo->getAge();
			$popestimate = $newInfo->getPopEstimate();
			$statement->bind_param('ssssss', $geoname, $geocode, $year, $gender, $age, $popestimate);
			$result = $statement->execute();
			if ($result == FALSE)
			{
				$errorMessage = $statement->error;
			}
			$statement->close();
			$connection->close();
			if ($result == TRUE)
			{
				// We need to return the status as 204 (no content) rather than 200 (OK) since
				// we are not returning any data
				$this->noContentResponse();
			}
			else
			{
				$this->errorResponse($errorMessage);
			}
		}
	}

	public function performPut($url, $parameters, $requestBody, $accept) 
	{
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;

		$newInfo = $this->extractInfoFromJSON($requestBody);
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$sql = "update info set Geo_name = ?, Geo_code= ?, Year = ?, Gender = ?, Age = ?, Population_Estimate = ? where id = ?";
			// We pull the fields of the information into local variables since 
			// the parameters to bind_param are passed by reference.
			$statement = $connection->prepare($sql);
			$Id = $newInfo->getId();
			$geoname = $newInfo->getGeoName();
			$geocode = $newInfo->getGeoCode();
			$year = $newInfo->getYear();
			$gender = $newInfo->getGender();
			$age = $newInfo->getAge();
			$popestimate = $newInfo->getPopEstimate();
			$statement->bind_param('ssssssi', $geoname, $geocode, $year, $gender, $age, $popestimate, $Id);
			$result = $statement->execute();
			if ($result == FALSE)
			{
				$errorMessage = $statement->error;
			}
			$statement->close();
			$connection->close();
			if ($result == TRUE)
			{
				// We need to return the status as 204 (no content) rather than 200 (OK) since
				// we are not returning any data
				$this->noContentResponse();
			}
			else
			{
				$this->errorResponse($errorMessage);
			}
		}
	}

    public function performDelete($url, $parameters, $requestBody, $accept) 
    {
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;
		
		if (count($parameters) == 2)
		{
			$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
			if (!$connection->connect_error)
			{
				$id = $parameters[1];
				$sql = "delete from info where id = ?";
				$statement = $connection->prepare($sql);
				$statement->bind_param('i', $id);
				$result = $statement->execute();
				if ($result == FALSE)
				{
					$errorMessage = $statement->error;
				}
				$statement->close();
				$connection->close();
				if ($result == TRUE)
				{
					// We need to return the status as 204 (no content) rather than 200 (OK) since
					// we are not returning any data
					$this->noContentResponse();
				}
				else
				{
					$this->errorResponse($errorMessage);
				}
			}
		}
    }


//	These functions return the required info from the relational database by executing the correct SQL queries
    private function getAllInfo()
    {
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;
	
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$query = "select id, Geo_name, Geo_code, Year, Gender, Age, Population_Estimate from info";
			if ($result = $connection->query($query))
			{
				while ($row = $result->fetch_assoc())
				{
					$this->info[] = new PopInfo($row["id"], $row["Geo_name"], $row["Geo_code"], $row["Year"], $row["Gender"], $row["Age"], $row["Population_Estimate"]);
				}
				$result->close();
			}
			$connection->close();
		}
	}	 
    private function getInfoById($id)
    {
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;
		
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$query = "select Geo_name, Geo_code, Year, Gender, Age, Population_Estimate from info where id = ?";
			$statement = $connection->prepare($query);
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->store_result();
			$statement->bind_result($geoname, $geocode, $year, $gender, $age, $popestimate);
			if ($statement->fetch())
			{
				return new PopInfo($id, $geoname, $geocode, $year, $gender, $age, $popestimate);
			}
			else
			{
				return null;
			}
			$statement->close();
			$connection->close();
		}
	}	
   private function getMalesInfo($age)
    {
	global $dbserver, $dbusername, $dbpassword, $dbdatabase;
	
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$query = "select id, Geo_name, Geo_code, Year, Gender, Age, Population_Estimate from info where Gender = 'Males' and Age = $age";
			if ($result = $connection->query($query))
			{
				while ($row = $result->fetch_assoc())
				{
					$this->info[] = new PopInfo($row["id"], $row["Geo_name"], $row["Geo_code"], $row["Year"], $row["Gender"], $row["Age"], $row["Population_Estimate"]);
				}
				$result->close();
			}
			$connection->close();
		}
	}	 	
	private function getFemalesInfo($age)
    {
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;
	
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$query = "select id, Geo_name, Geo_code, Year, Gender, Age, Population_Estimate from info where Gender = 'Females' and Age = $age";
			if ($result = $connection->query($query))
			{
				while ($row = $result->fetch_assoc())
				{
					$this->info[] = new PopInfo($row["id"], $row["Geo_name"], $row["Geo_code"], $row["Year"], $row["Gender"], $row["Age"], $row["Population_Estimate"]);
				}
				$result->close();
			}
			$connection->close();
		}
	}
	 private function getAllPersonsInfo($age)
    {
		global $dbserver, $dbusername, $dbpassword, $dbdatabase;
	
		$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
		if (!$connection->connect_error)
		{
			$query = "select id, Geo_name, Geo_code, Year, Gender, Age, Population_Estimate from info where Gender = 'All Persons' and Age = $age";
			if ($result = $connection->query($query))
			{
				while ($row = $result->fetch_assoc())
				{
					$this->info[] = new PopInfo($row["id"], $row["Geo_name"], $row["Geo_code"], $row["Year"], $row["Gender"], $row["Age"], $row["Population_Estimate"]);
				}
				$result->close();
			}
			$connection->close();
		}
	}
  

    private function extractInfoFromJSON($requestBody)
    {
		// This function is needed because of the perculiar way json_decode works. 
		// By default, it will decode an object into a object of type stdClass.  There is no
		// way in PHP of casting a stdClass object to another object type.  So we use the
		// approach of decoding the JSON into an associative array (that's what the second
		// parameter set to true means in the call to json_decode). Then we create a new
		// Info object using the elements of the associative array.  

//      	Note that we are not
		// doing any error checking here to ensure that all of the items needed to create a new
		// Info object are provided in the JSON - we really should be.
		$infoArray = json_decode($requestBody, true);
		$info = new PopInfo($infoArray['Id'],
						 $infoArray['Geo_name'],
						 $infoArray['Geo_code'],
						 $infoArray['Year'],
						 $infoArray['Gender'],
						 $infoArray['Age'],
						 $infoArray['PopEstimate']);
		unset($infoArray);
		return $info;
	}
}
?>

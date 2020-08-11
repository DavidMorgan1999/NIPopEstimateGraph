<?php


//This allows the creation of the PopInfo object which can then be populated with information brought in from the relational database
class PopInfo
{
    public $Id;
    public $GeoName;
    public $GeoCode;
    public $Year;
    public $Gender;
    public $Age;
    public $PopEstimate;

    public function __construct($id, $geoname, $geocode, $year, $gender, $age, $popestimate)
    {
        $this->Id = $id;
        $this->GeoName = $geoname;
        $this->GeoCode = $geocode;
        $this->Year = $year;
        $this->Gender = $gender;
        $this->Age = $age;
        $this->PopEstimate = $popestimate;
    }

    public function getId()
    {
        return $this->Id;
    }

    public function getGeoName()
    {
        return $this->GeoName;
    }

    public function getGeoCode()
    {
        return $this->GeoCode;
    }

    public function getYear()
    {
        return $this->Year;
    }

    public function getGender()
    {
        return $this->Gender;
    }

    public function getAge()
    {
        return $this->Age;
    }

    public function getPopEstimate()
    {
        return $this->PopEstimate;
    }
}
?>

<?php
$host="localhost";
$user="root";
$password="";
$dbname="inscription_db";

$conn= new mysqli($host,$user,$password,$dbname);

if($conn->connect_error)
{
     die("Échec de la connexion : " . $conn->connect_error);
}
else
    echo"Connexion reussie";
?>
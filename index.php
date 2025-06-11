<!DOCTYPE html>
<html>

<head>
    <title>Candidat</title>
</head>

<body>
    <h2>Inscription candidat</h2>
    <form action="enregistrer.php" , method="POST">
        Nom complet:
        <input type="text" name="nom" required><br><br>
        Email:
        <input type="email" name="email" required><br><br>
        Téléphone :
        <input type="text" name="telephone" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
<style>
    form{
        background-color: green;
    }
</style>
</body>

</html>
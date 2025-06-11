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
        body {
            background-color: green;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: white;
            padding: 20px;
            width: 400px;
            border-radius: 10px;
           
        }

        .form-group {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .form-group label {
            width: 120px;
            font-weight: bold;
        }

        .form-group input {
            flex: 1;
            padding: 5px;
        }

        
       
    
</style>
</body>

</html>
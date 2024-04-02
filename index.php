<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pathway+Extreme:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit; background: none; border: none;">Bibliothèque</a></h1>
        <nav>
            <a href="mention_legal.html" id="mention-link">Mentions Légales</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="compte.php" id="account-link">Compte</a>
                <a href="favoris.php" id="favoris-link">Mes favoris</a>
                <a href="logout.php" id="logout-link">Déconnexion</a>
            <?php else: ?>
                <a href="Login.html" id="login-link">Login</a>
                <a href="Signup_.php" id="signup-link">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section id="search-section">
            <form id="search-form">
                <input type="text" id="search-input" placeholder="Rechercher un livre">
                <button type="submit" id='search-button'>Rechercher</button>
            </form>
            <div id="results"></div>
        </section>
    </main>

    <script>
    // Code JavaScript pour ajouter le bouton "Ajouter aux favoris" et la fonction associée
    document.getElementById('search-form').addEventListener('submit', function(event) {
        event.preventDefault();

        var searchInput = document.getElementById('search-input').value;
        var url = 'https://www.googleapis.com/books/v1/volumes?q=' + encodeURIComponent(searchInput) + '&subject:geography&langRestrict=fr';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                var resultsDiv = document.getElementById('results');
                resultsDiv.innerHTML = '';
                data.items.forEach(item => {
                    var bookDiv = document.createElement('div');
                    bookDiv.className = 'book-result';

                    var title = document.createElement('h2');
                    title.textContent = item.volumeInfo.title;
                    bookDiv.appendChild(title);

                    if (item.volumeInfo.imageLinks && item.volumeInfo.imageLinks.thumbnail) {
                        var img = document.createElement('img');
                        img.src = item.volumeInfo.imageLinks.thumbnail;
                        bookDiv.appendChild(img);
                    }

                    var addToFavoritesBtn = document.createElement('button');
                    addToFavoritesBtn.textContent = 'Ajouter aux favoris';
                    addToFavoritesBtn.addEventListener('click', function() {
                        addToFavorites(item.volumeInfo.title, item.volumeInfo.authors[0]);
                    });
                    bookDiv.appendChild(addToFavoritesBtn);

                    resultsDiv.appendChild(bookDiv);
                });
            })
            .catch(error => console.error('Erreur :', error));
    });

    function addToFavorites(title, author) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_to_favorites.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send('title=' + encodeURIComponent(title) + '&author=' + encodeURIComponent(author));
    }
    </script>
</body>
</html>

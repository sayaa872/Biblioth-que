// Lorsque le formulaire est soumis
document.getElementById('search-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Récupérer la valeur de l'input de recherche
    var searchInput = document.getElementById('search-input').value;

    // Construire l'URL de l'API avec la clé API et la valeur de l'input de recherche
    var url = 'https://www.googleapis.com/books/v1/volumes?q=' + encodeURIComponent(searchInput) + '&subject:geography&langRestrict=fr';

    // Envoyer une requête à l'API Google Books
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Afficher les résultats de la recherche
            var resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = ''; // Effacer les résultats précédents
            data.items.forEach(item => {
                var bookDiv = document.createElement('div');
                bookDiv.className = 'book-result'; // Ajouter une classe CSS

                var title = document.createElement('h2');
                title.textContent = item.volumeInfo.title;
                bookDiv.appendChild(title);

                if (item.volumeInfo.imageLinks && item.volumeInfo.imageLinks.thumbnail) {
                    var img = document.createElement('img');
                    img.src = item.volumeInfo.imageLinks.thumbnail;
                    bookDiv.appendChild(img);
                }

                resultsDiv.appendChild(bookDiv);
            });
        })
        .catch(error => console.error('Erreur :', error));
});
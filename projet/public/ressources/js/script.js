// Carousel

let currentIndex = 0;
const imagesContainer = document.querySelector('.carousel-images');
const totalImages = document.querySelectorAll('.carousel-images img').length;

function updateCarousel() {
    const offset = currentIndex * -500; // Assurez-vous que cette valeur correspond à la largeur de vos images
    imagesContainer.style.transform = `translateX(${offset}px)`;
}

imagesContainer.addEventListener('click', (e) => {
    const rect = e.target.getBoundingClientRect();
    const x = e.clientX - rect.left; // x position within the element.

    if (x < rect.width / 2) {
        // Clic sur la moitié gauche pour aller à l'image précédente
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalImages - 1; // Revenir à la dernière image si on est à la première
        }
    } else {
        // Clic sur la moitié droite pour aller à l'image suivante
        if (currentIndex < totalImages - 1) {
            currentIndex++;
        } else {
            currentIndex = 0; // Revenir à la première image si on est à la dernière
        }
    }
    updateCarousel();
});

// Changer les images toutes les 5 secondes
setInterval(() => {
    currentIndex = (currentIndex + 1) % totalImages; // Incrémenter l'index, et revenir à 0 si on dépasse le nombre total d'images
    updateCarousel();
}, 5000); // 5000 ms = 5 secondes

// Mots JPN 

document.addEventListener('DOMContentLoaded', function() {
    const motsDiv = document.getElementById('motsJaponais');

    // Fonction pour charger un mot aléatoire et sa traduction
    function loadRandomWord() {
        fetch('public/ressources/json/motsJPN.json')
            .then(response => response.json())
            .then(data => {
                const index = Math.floor(Math.random() * data.length);
                const mot = data[index];
                motsDiv.innerHTML = `<h3>Mot du jour : ${mot.japonais}</h3><h3>Traduction : ${mot.francais}<h3>`;
            })
            .catch(error => console.error('Erreur lors du chargement des mots japonais :', error));
    }

    // Charger immédiatement un mot au démarrage
    loadRandomWord();

    // Ensuite, changer le mot toutes les 10 secondes (10000 millisecondes)
    setInterval(loadRandomWord, 10000);
});
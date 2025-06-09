document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");

    // Kontrollerar om det finns några länkar på sidan
    if (document.querySelectorAll('a').length === 0) {
        console.log("Inga länkar hittades på sidan.");
    }

    document.querySelectorAll('a').forEach(function(link) {
        console.log("Länk hittad: ", link.href);

        link.addEventListener('click', function(e) {
            console.log("Länk klickad: ", this.href);

            // Förhindra standardlänkbeteendet
            e.preventDefault();

            // Lägg till klassen 'fade-out' till body
            document.body.classList.add('fade-out');
            console.log("Fade-out effekt startad");

            // Vänta tills övergången är klar innan du navigerar till den nya sidan
            setTimeout(function() {
                window.location.href = link.href;
                console.log("Navigerar till: ", link.href);
            }, 500); // Tiden här bör matcha övergångstiden i CSS
        });
    });

    window.addEventListener('pageshow', function() {
        console.log("Sida visad: ", window.location.href);
        document.body.classList.remove('fade-out');
    });
});

const stars = [];
const documents = document.getElementsByClassName("document");

for (let i = 1; i <= 5; i++) {
  stars[i] = document.getElementById("minimal-rating-" + i);

  stars[i].addEventListener("click", (e) => {
    for (let j = 1; j <= i; j++) {
      stars[j].classList.add("fas");
      stars[j].classList.remove("far");
    }
    for (let j = i + 1; j <= 5; j++) {
      stars[j].classList.remove("fas");
      stars[j].classList.add("far");
    }
    updateDocuments(i);
    filterDocuments(i);
    console.log(i);
  });
}

function filterDocuments(i) {
  for (let document in documents) {
    if (documents[document].tagName == "DIV") {
      if (documents[document].getAttribute("data-rating") < i) {
        documents[document].classList.add("hidden");
      } else {
        documents[document].classList.remove("hidden");
      }
    }
  }
}

function updateDocuments(rating) {
  const url = "/documents/get-documents-by-rating/" + rating;
  console.log(url);
  fetch(url)
    // fetch() renvoie une promesse. Lorsque nous aurons reçu
    // une réponse du serveur, le gestionnaire then() de la
    // promesse sera appelé avec la réponse
    .then((response) => {
      // Le gestionnaire lève une erreur si la requête a échoué.
      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }
      // Sinon, si la requête a réussi, le gestionnaire récupère
      // la réponse sous forme de texte en appelant response.text(),
      // Et renvoie immédiatement la promesse renvoyée par response.text().
      console.log(response.text());
      //return response.text();
    });
}

const titleInput = document.querySelector("input#title");
const form = document.querySelector("#form_filter_documents");
const resultsElement = document.querySelector("#document_results");

let doc;

titleInput.addEventListener("keyup", async (e) => {
  const formData = new FormData(form);

  try {
    const response = await fetch("/documents/get-filtered-documents", {
      method: "POST",
      // Set the FormData instance as the request body
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        const parser = new DOMParser();
        doc = parser.parseFromString(html, "text/html");
      });
    // let data = JSON.parse(await response.json());
    resultsElement.innerHTML = doc.body.innerHTML;
  } catch (e) {
    console.error(e);
  }
});

// const stars = [];
// const documents = document.getElementsByClassName("document");
// const documentResultsElement = document.getElementById("document_results");

// for (let i = 1; i <= 5; i++) {
//   stars[i] = document.getElementById("minimal-rating-" + i);

//   stars[i].addEventListener("click", (e) => {
//     for (let j = 1; j <= i; j++) {
//       stars[j].classList.add("fas");
//       stars[j].classList.remove("far");
//     }
//     for (let j = i + 1; j <= 5; j++) {
//       stars[j].classList.remove("fas");
//       stars[j].classList.add("far");
//     }
//     // updateDocuments(i);
//     filterDocuments(i);
//     console.log(i);
//   });
// }

// async function filterDocuments(i) {
//   const updatedDocuments = await getDocuments(i);
//   documentResultsElement.innerHTML = "";
//   updatedDocuments.forEach((document) => {
//     documentResultsElement.innerHTML += `
//     <a href="{{ path('document_show', {'slug': ${document.slug}}) }}">
// 						<div class="col document" data-rating="{{${document.ratingAverage}}}">
// 							<div class="p-3 border bg-light">
// 								<h2 class="text-center">{{ ${document.title} }}</h2>
// 								<div class="mx-auto text-center">
// 									proposé le
// 									{{ ${document.uploadedAt} | format_datetime("short", "none", (locale = "fr"))}}
// 									par
// 									{{ ${document.author.username} }}
// 								</div>
// 								<div class="mx-auto text-center">
// 									{% include 'partials/_rating.html.twig' %}
// 									{{ ${document.ratingAverage} }}
// 								</div>

// 							</div>
// 						</div>
// 					</a>`;
//   });
//   // for (let document in documents) {
//   //   if (documents[document].tagName == "DIV") {
//   //     if (documents[document].getAttribute("data-rating") < i) {
//   //       documents[document].classList.add("hidden");
//   //     } else {
//   //       documents[document].classList.remove("hidden");
//   //     }
//   //   }
//   // }
// }

// async function getDocuments(rating) {
//   const url = "/documents/get-documents-by-rating/" + rating;

//   const result = await (
//     await fetch(url, {
//       headers: {
//         "Content-Type": "application/json",
//       },
//     })
//   ).json();

//   const json = JSON.parse(result);

//   if (json.error) {
//     throw new Error(`Erreur HTTP : ${response.status}`);
//   }

//   return json;
// }

//   // await fetch(url)
//   //   // fetch() renvoie une promesse. Lorsque nous aurons reçu
//   //   // une réponse du serveur, le gestionnaire then() de la
//   //   // promesse sera appelé avec la réponse
//   //   .then((response) => {
//   //     // Le gestionnaire lève une erreur si la requête a échoué.
//   //     if (!response.ok) {
//   //       throw new Error(`Erreur HTTP : ${response.status}`);
//   //     }
//   //     // Sinon, si la requête a réussi, le gestionnaire récupère
//   //     // la réponse sous forme de texte en appelant response.text(),
//   //     // Et renvoie immédiatement la promesse renvoyée par response.text().
//   //     console.log("response.text", response.text());
//   //     //return response.text();
//   //   });
// }

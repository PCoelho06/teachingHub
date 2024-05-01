const downloadsNumberElement = document.getElementById("downloadsNumber");
const downloadButton = document.getElementById("downloadButton");

downloadButton.addEventListener("click", (e) => {
  const response = updateDownloads(
    parseInt(downloadButton.getAttribute("data-id"))
  );
  response
    .then(function (result) {
      if (result.proceed) {
        downloadsNumberElement.textContent =
          parseInt(downloadsNumberElement.textContent) + 1;
      }
    })
    .then(() => {
      setTimeout(() => {
        window.location.href += "?reload";
      }, 500);
    });
});

async function updateDownloads(documentId) {
  const url = "/documents/update-downloads/" + documentId;

  const result = await (
    await fetch(url, {
      headers: {
        "Content-Type": "application/json",
      },
    })
  ).json();

  const json = JSON.parse(result);

  if (json.error) {
    throw new Error(`Erreur HTTP : ${response.status}`);
  }

  return json;
}

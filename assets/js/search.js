const starRatingLabels = document.querySelectorAll(".form-check-label");
const starRatingInputs = document.querySelectorAll("input[type=radio]");

Array.from(starRatingInputs).forEach((input) => {
  input.addEventListener("change", (e) => {
    fillStars(e.target.value);
  });
});

Array.from(starRatingInputs).forEach((element) => {
  if (element.checked) {
    fillStars(element.value);
  }
});

function fillStars(value) {
  Array.from(starRatingLabels).forEach((star) => {
    if (star.dataset.rating <= value) {
      star.firstElementChild.classList.remove("far");
      star.firstElementChild.classList.add("fas");
    } else {
      star.firstElementChild.classList.add("far");
      star.firstElementChild.classList.remove("fas");
    }
  });
}

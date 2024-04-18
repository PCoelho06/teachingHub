const starRatingLabels = document.querySelectorAll(".form-check-label");
const starRatingInputs = document.querySelectorAll("input[type=radio]");

Array.from(starRatingInputs).forEach((element) => {
  if (element.checked) {
    fillStars(element.value);
  }
});

Array.from(starRatingLabels).forEach((ratingStar) => {
  ratingStar.addEventListener("click", (e) => {
    console.log(ratingStar.getAttribute("for"));
    const ratingStarInput = document.querySelector(
      "#" + ratingStar.getAttribute("for")
    );
    ratingStarInput.checked = true;
    fillStars(ratingStarInput.value);
  });
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

const ratingStars = document.getElementsByClassName("rating-input");
const commentRating = document.getElementById("comment_rating");

Array.from(ratingStars).forEach((ratingStar) => {
  ratingStar.addEventListener("click", (e) => {
    console.log(ratingStar.value);
    commentRating.value = ratingStar.value;
  });
});

// #################

const starRatingLabels = document.querySelectorAll(".form-check-label");
const starRatingInputs = document.querySelectorAll("input[type=radio]");

Array.from(starRatingInputs).forEach((element) => {
  if (element.checked) {
    console.log(element.value);
    for (let star in starRatingLabels) {
      if (starRatingLabels[star].dataset.rating <= element.value) {
        starRatingLabels[star].firstElementChild.classList.remove("far");
        starRatingLabels[star].firstElementChild.classList.add("fas");
      }
    }
  }
});

// for (let star in starRatingLabels) {
//   starRatingLabels[star].firstElementChild.addEventListener(
//     "mouseover",
//     (e) => {
//       Array.from(starRatingLabels).forEach((element) => {
//         if (element.dataset.rating <= e.target.parentElement.dataset.rating) {
//           element.firstElementChild.classList.remove("far");
//           element.firstElementChild.classList.add("fas");
//         } else {
//           element.firstElementChild.classList.add("far");
//           element.firstElementChild.classList.remove("fas");
//         }
//       });
//     }
//   );

//   starRatingLabels[star].firstElementChild.addEventListener("mouseout", (e) => {
//     Array.from(starRatingLabels).forEach((element) => {
//       element.firstElementChild.classList.add("far");
//       element.firstElementChild.classList.remove("fas");
//     });
//   });
// }

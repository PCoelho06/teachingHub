const ratingStars = document.getElementsByClassName("rating-input");
const commentRating = document.getElementById("comment_rating");

console.log(ratingStars);

Array.from(ratingStars).forEach((ratingStar) => {
  ratingStar.addEventListener("click", (e) => {
    console.log(ratingStar.value);
    commentRating.value = ratingStar.value;
  });
});

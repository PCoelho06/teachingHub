const stars = [];

for (let i = 1; i <= 5; i++) {
  stars[i] = document.getElementById("minimal-rating-" + i);

  stars[i].addEventListener("mouseover", (e) => {
    for (let j = 1; j <= i; j++) {
      stars[j].classList.toggle("fas");
      stars[j].classList.toggle("far");
    }
  });

  stars[i].addEventListener("mouseout", (e) => {
    for (let j = 1; j <= i; j++) {
      stars[j].classList.toggle("fas");
      stars[j].classList.toggle("far");
    }
  });
}

console.log(stars);

// firstStar.addEventListener("mouseover", (e) => {
//   firstStar.classList.toggle("fas");
//   firstStar.classList.toggle("far");
// });

// firstStar.addEventListener("mouseout", (e) => {
//   firstStar.classList.toggle("fas");
//   firstStar.classList.toggle("far");
// });

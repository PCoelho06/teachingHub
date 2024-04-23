const userAvatarInput = document.getElementById("user_avatar");
const userAvatarPreview = document.getElementById("avatar_preview");

userAvatarInput.addEventListener("change", function (e) {
  userAvatarPreview.setAttribute("src", e.target.value);
});

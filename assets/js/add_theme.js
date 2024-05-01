const addThemeBtn = document.getElementById("add_theme");
const addThemeElement = document.getElementById("add_theme_element");
const documentLevelsElement = document.getElementById("document_levels");
const documentSubjetsElement = document.getElementById("document_subjects");

addThemeBtn.addEventListener("click", () => {
  addThemeElement.innerHTML = `
    <div id="new_theme_form" class="mb-3">
        <label for="document_newTheme_name" class="form-label required">Nouvelle thématique</label>
        <input type="text" id="document_newTheme_name" name="document[newTheme][name]" required="required" maxlength="255" class="form-control" data-np-intersection-state="visible">
        <button type="button" id="submit_new_theme" class="btn btn-primary mt-2">Ajouter</button>
    </div>`;
  const submitNewThemeBtn = document.getElementById("submit_new_theme");
  const newThemeForm = document.getElementById("new_theme_form");

  submitNewThemeBtn.addEventListener("click", async () => {
    const newThemeName = document.getElementById(
      "document_newTheme_name"
    ).value;
    let themeLevels = [];
    let themeSubjects = [];

    for (child in documentLevelsElement.children) {
      if (
        child < documentLevelsElement.children.length &&
        documentLevelsElement.children[child].firstChild.checked
      ) {
        themeLevels.push(
          documentLevelsElement.children[child].firstChild.value
        );
      }
    }

    for (child in documentSubjetsElement.children) {
      if (
        child < documentSubjetsElement.children.length &&
        documentSubjetsElement.children[child].firstChild.checked
      ) {
        themeSubjects.push(
          documentSubjetsElement.children[child].firstChild.value
        );
      }
    }

    const result = await (
      await fetch("/theme/ajouter-theme", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: newThemeName,
          levels: themeLevels,
          subjects: themeSubjects,
        }),
      })
    ).json();

    result.then(location.reload());
  });
});

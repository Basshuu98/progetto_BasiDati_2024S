<div class="container is-max-desktop box">

  <div class="field">
    <label class="label"><i class="fa-solid fa-search mr-2"></i>Ricerca nella tabella:</label>
    <div class="control has-icons-left">
      <input id="filter" class="input" type="text" placeholder="Filtra" oninput="filterTable()">
      <span class="icon is-small is-left">
      <i class="fa-solid fa-search"></i>
    </span>
    </div>
  </div>

  <label class="checkbox mt-2">
    <input id="highlight" type="checkbox" checked onclick="filterTable()">
    Evidenzia risultati della ricerca
  </label>

  <label class="checkbox ml-4 mt-2">
    <input id="hide" type="checkbox" onclick="filterTable()">
    Nascondi valori non corrispondenti alla ricerca
  </label>

</div>

<script>

  function filterTable() {
    const dom = [...document.querySelectorAll("tbody > tr")]

    dom.forEach(tr => {
      tr.classList.remove("focused")
      tr.classList.remove("hidden")
    }) 

    target = document.getElementById("filter").value.toLowerCase()
    highlight = document.getElementById("highlight").checked
    hide = document.getElementById("hide").checked

    if (!target) {
      window.history.replaceState("", "", window.location.href.split("?")[0]) 
      return
    }

    window.history.replaceState("", "", `?filter=${target}&highlight=${highlight}&hide=${hide}`)

    dom.forEach(tr => {
      if (highlight) {
        if (tr.textContent.toLowerCase().includes(target)) {
          tr.classList.add("focused")
        }
      }
      if (hide) {
        if (!tr.textContent.toLowerCase().includes(target)) {
          tr.classList.add("hidden")
        }
      }
    })
  }

  setTimeout(function() {
    const url = new URL(window.location.href)
    filter = url.searchParams.get("filter")
    highlight = url.searchParams.get("highlight")
    hide = url.searchParams.get("hide")

    if (filter) {
      document.getElementById("filter").value = filter
      document.getElementById("highlight").checked = (highlight == "true");
      document.getElementById("hide").checked = (hide == "true");
    }

    filterTable()
  }, 500)

</script>
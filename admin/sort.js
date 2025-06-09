// sort.js - v1

// ******************* START sort.js - v1 *******************

// Enkel tabellsortering för dynamiska listor
window.makeTableSortable = function (tableElement) {
  const headers = tableElement.querySelectorAll("th");
  headers.forEach((th, index) => {
    th.style.cursor = "pointer";
    let ascending = true;

    th.addEventListener("click", () => {
      const rows = Array.from(tableElement.querySelectorAll("tbody tr"));
      rows.sort((a, b) => {
        const aText = a.children[index].textContent.trim();
        const bText = b.children[index].textContent.trim();

        return ascending
          ? aText.localeCompare(bText, "sv", { numeric: true })
          : bText.localeCompare(aText, "sv", { numeric: true });
      });

      ascending = !ascending;
      rows.forEach((row) => tableElement.querySelector("tbody").appendChild(row));
      console.log(`↕️ Sorterade kolumn ${index} (${ascending ? "stigande" : "fallande"})`);
    });
  });
};

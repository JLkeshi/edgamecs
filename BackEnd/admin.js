// Search Filter for Leaderboard
document.getElementById("search-leaderboard").addEventListener("keyup", function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll("#rankings tbody tr");

    rows.forEach((row) => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchValue) ? "" : "none";
    });
});

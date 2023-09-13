console.log("working");

let searchForm = document.getElementById("search-form");
document.getElementById("search-icon").addEventListener("click", (e) => {
  let searchFormDisplay = searchForm.style.display;
  console.log(searchFormDisplay);
});

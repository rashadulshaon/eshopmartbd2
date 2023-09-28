document.getElementById("search-icon").addEventListener("click", (e) => {
  let mobileSearch = document.getElementById("mobileSearch");

  // mobileSearch.style.display = 'flex';

  if (mobileSearch.style.display != 'flex') {
    mobileSearch.style.display = 'flex';
  } else {
    mobileSearch.style.display = 'none';
  }
});

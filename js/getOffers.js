window.addEventListener("load", () => {
  fetchOfferJson();
  fetchOffer();
  setInterval(fetchOffer, 5000);
});
// fetches the html offer and displays it
const fetchOffer = () => {
  try {
    fetch("../getOffers.php")
      .then((res) => res.text())
      .then((data) => {
        document.getElementById("offers").innerHTML = data;
      })
      .catch((err) => {
        alert(
          "Something went wrong while retrieving a special offer for you. Please try to reload the page."
        );
      });
  } catch (err) {
    alert(
      "Something went wrong while retrieving a special offer for you. Please try to reload the page."
    );
  }
};
// fetches offer in json format and displays it in a formatted way
const fetchOfferJson = () => {
  try {
    fetch("../getOffers.php?useJSON")
      .then((res) => res.json())
      .then((data) => {
        // create elements for each json variable
        let title = document.createElement("h5");
        title.textContent = data.bookTitle;
        let price = document.createElement("p");
        price.textContent = data.bookPrice;
        let desc = document.createElement("p");
        desc.textContent = data.catDesc;
        // a styled box for the grouping all attributes
        let paragraph = document.createElement("div");
        paragraph.className = "border border-dark p-1";
        // adding everything to the div
        paragraph.appendChild(title);
        paragraph.appendChild(price);
        paragraph.appendChild(desc);
        //displaying it
        document.getElementById("JSONoffers").appendChild(paragraph);
      })
      // for catching errors from the server, e.g. if the file is not found
      .catch((err) => {
        alert(
          "Something went wrong while retrieving a special offer for you. Please try to reload the page."
        );
      });
    // for catching errors in the code and creating elements
  } catch (err) {
    alert(
      "123123Something went wrong while retrieving a special offer for you. Please try to reload the page."
    );
  }
};

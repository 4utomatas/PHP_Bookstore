window.addEventListener("load", function () {
  "use strict";
  questionD();

  // questions a and b
  // terms and conditions text element
  const terms = document.getElementById("termsText");
  // terms and cond. check box element
  const termsCb = terms.querySelector("input[name=termsChkbx]");
  // the whole form element
  const form = document.getElementById("orderForm");
  // the submit button
  const submitBtn = form.querySelector("input[name=submit]");
  // when terms and conditions checkbox is clicked:
  termsCb.onclick = styletc;
  // trigger a function to check if all conditions are met to submit a form
  form.onsubmit = checkFormValid;
  // this function styles the terms and conditions text element and enables/disables submit button
  function styletc() {
    if (terms.style.color === "rgb(255, 0, 0)") {
      terms.style.color = "#000";
      terms.style.fontWeight = "normal";
      // question b
      submitBtn.disabled = false;
    } else {
      terms.style.color = "#FF0000";
      terms.style.fontWeight = "bold";
      // question b
      submitBtn.disabled = true;
    }
  }
  // question C
  // customer details section
  const section = document.getElementById("placeOrder");
  // customer type select element
  const type = section.querySelector("select[name=customerType]");
  // customer inputs div
  const cust = document.getElementById("retCustDetails");
  // company inputs div
  const trade = document.getElementById("tradeCustDetails");
  // question E
  // initially both are hidden
  cust.style.visibility = "hidden";
  trade.style.visibility = "hidden";
  // when the customer type select element is clicked
  type.onclick = displayCustType;
  // display customer type when it is selected. question E
  function displayCustType() {
    if (type.value === "trd") {
      // company
      cust.style.visibility = "hidden";
      trade.style.visibility = "visible";
    } else if (type.value === "ret") {
      // customer
      cust.style.visibility = "visible";
      trade.style.visibility = "hidden";
    } else {
      // if the user escapes or presses the top row
      cust.style.visibility = "hidden";
      trade.style.visibility = "hidden";
    }
  }
  // check if the form is valid
  function checkFormValid(e) {
    // prevent the form from submitting/default behaviour
    e.preventDefault();
    // retrieves inputs
    const fname = section.querySelector("input[name=forename]");
    const sname = section.querySelector("input[name=surname]");
    const cname = section.querySelector("input[name=companyName]");
    // a variable for checking if the form can be submitted
    let error = false;

    // check if the user selected customer type
    if (type.value !== "ret" && type.value !== "trd") {
      alert("Please, select customer type.");
      error = true;
    }
    // check if user filled inputs
    if (
      type.value === "ret" &&
      (fname.value === null || fname.value === "" || sname.value === null || sname.value === "")
    ) {
      alert("Enter your name and surname, please.");
      error = true;
    }
    // check if user filled inputs
    if (type.value === "trd" && (cname.value === "" || cname.value === null)) {
      alert("Enter your company name, please.");
      error = true;
    }

    // retrieve all books
    const books = document.getElementById("orderBooks").querySelectorAll("input[type=checkbox]");
    let checkedBooks = 0;
    books.forEach((book) => {
      if (book.checked) {
        checkedBooks++;
      }
    });
    // check if any books were selected
    if (checkedBooks === 0) {
      alert("You need to select a book");
      error = true;
    }
    // if everything is correct, the form is considered successfully submitted,
    // a message is displayed
    if (error === false) {
      alert("There were no errors, you successfully completed the form");
    }
  }
  // question d
  function questionD() {
    // setting the eventhandler for each checkbox
    const bookCheckboxes = document
      .getElementById("orderBooks")
      .querySelectorAll("input[type=checkbox]");
    for (let i = 0; i < bookCheckboxes.length; i++) {
      bookCheckboxes[i].onclick = setTotalPrice;
    }
    // setting event handlers to all radio buttons
    const shippingRadios = document
      .getElementById("collection")
      .querySelectorAll("input[type=radio]");
    for (let i = 0; i < shippingRadios.length; i++) shippingRadios[i].onclick = setShippingPrice;
  }
  // event handler for when user changes shipping method
  function setShippingPrice(event) {
    if (event.target.checked) {
      // get shipping price and set it
      const changed = setDataSetShipping();
      // update total price if the method was changed
      if (changed) {
        const totalInput = document.getElementById("checkCost").querySelector("input[name=total]");
        if (totalInput.dataset.books !== undefined && parseFloat(totalInput.dataset.books) !== 0) {
          console.log(totalInput.dataset.books);
          totalInput.value = (
            parseFloat(totalInput.dataset.books) + parseFloat(totalInput.dataset.shipping)
          ).toFixed(2);
        } else if (
          totalInput.dataset.books !== undefined &&
          parseFloat(totalInput.dataset.books) === 0
        ) {
          // to fixed(2)
          totalInput.value = 0;
        }
      }
    }
  }
  // sets the selected shipping price to input[name=total] dataset
  function setDataSetShipping() {
    const totalInput = document.getElementById("checkCost").querySelector("input[name=total]");
    const shipping = getShippingPrice();
    if (shipping !== -1) {
      // if shipping data is not set, set it
      if (totalInput.dataset.shipping === undefined) {
        totalInput.dataset.shipping = shipping;
        return true;
      } else if (totalInput.dataset.shipping !== shipping) {
        totalInput.dataset.shipping = shipping;
        return true;
      }
      return false;
    }
  }
  // get the currently selected shipping price
  function getShippingPrice() {
    const radios = document.getElementById("collection").querySelectorAll("input[type=radio]");
    for (let i = 0; i < radios.length; i++) {
      if (radios[i].checked) {
        return radios[i].dataset.price;
      }
    }
    return -1;
  }
  // set total price
  function setTotalPrice(event) {
    const total = document.getElementById("checkCost").querySelector("input[name=total]");
    setDataSetShipping();
    // set val if value was not initialised
    if (total.value === "" || total.value === null) {
      total.value = 0;
    }

    // parse values as floats
    const bookPrice = event.target.dataset.price;
    const bookPriceParsed = parseFloat(bookPrice) === NaN ? 0 : parseFloat(bookPrice);
    let tval = total.dataset.books === undefined ? 0 : parseFloat(total.dataset.books);

    // check if checkbox was (un)checked and change the price accordingly
    if (event.target.checked) {
      tval = tval + bookPriceParsed;
    } else {
      tval = tval - bookPriceParsed;
    }
    // save total price of books
    total.dataset.books = tval.toFixed(2);
    if (parseFloat(total.dataset.books) === 0) {
      total.value = 0;
    } else {
      total.value = (tval + parseFloat(total.dataset.shipping)).toFixed(2);
    }
  }
});

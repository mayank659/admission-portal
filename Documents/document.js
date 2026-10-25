const form = document.querySelector(".form");
const field = document.querySelector(".field");
let data;

async function fetched() {
  field.innerHTML = "";
  try {
    const response = await fetch("document.php?fetch=1");
    if (!response.ok) {
      alert("There is a problem");
      return;
    }
    data = await response.json();
    /*    const text = await response.text(); */

    data.forEach((name) => {
      const box = document.createElement("div");
      box.className = "box";
      box.innerHTML = `
                <input type="text" value="${name.name}" id="input-${name.sno}"></input>
                <button class="update" data-sno="${name.sno}">Update</button>
                <button class="delete" style="background-color: ${name.is_active == 0 ? 'gray' : ''};" data-sno="${name.sno}">${name.is_active == 1 ? "Deactivate" : "Activate"}</button>
        `;
      field.appendChild(box);
    });
  } catch (error) {
    console.log(error);
    alert("Something went wrong !!");
  }
}

fetched();

field.addEventListener("click", async (e) => {
  const sno = e.target.getAttribute("data-sno");
  if (e.target.classList.contains("update")) {
    const input = document.querySelector(`#input-${sno}`);
    const newname = input.value;
    const param = new URLSearchParams({
      newname: newname,
      sno: sno,
    });
    const response = await fetch(`document.php?${param}`);
    if (!response.ok) {
      alert("There is a problem !!");
      return;
    }
    alert("Updated Successfully !!");
    fetched();
  } 
  else if (e.target.classList.contains("delete")) {
    const documentData = data.find((item) => item.sno == sno);

    const action = documentData.is_active == 1 ? "deactivate" : "activate";

    const confirmAction = confirm(
      `Are you sure you want to ${action} this document?`,
    );

    if (!confirmAction) {
      return;
    }

    const param = new URLSearchParams({
      sno: sno,
      action: action,
    });

    const response = await fetch(`document.php?${param}`);

    if (!response.ok) {
      alert("There is a problem!");
      return;
    }

    alert(`${action} successful!`);
    fetched();
  }
});



form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formdata = new FormData(form);
  if (formdata.get("document").trim() != "") {
    try {
      const param = new URLSearchParams({
        document: formdata.get("document").trim(),
      });
      const response = await fetch(`document.php?${param}`);
      if (!response.ok) {
        alert("There is a problem !!");
        return;
      }
      alert("Added Successfull !!");
      fetched();
    } catch (error) {
      console.log(error);
      alert("Something went wrong !!");
    }
  }
  if (formdata.get("document").trim() == "") {
    alert("Please Enter Document !!");
  }
});

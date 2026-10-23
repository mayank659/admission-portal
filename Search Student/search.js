let form = document.querySelector("form");
let crossbtn = document.querySelector(".crossbtn");
let print = document.querySelector(".print");
let screen = document.querySelector(".screen");
let studentdata = [];

form.addEventListener("submit", (e) => {
  e.preventDefault();
  const formdata = new FormData(form);
  const syllabus = formdata.get("syllabus");
  const session = formdata.get("session");
  const name = formdata.get("name");
  const guardian = formdata.get("guardian");
  const category = formdata.get("category");
  const admission_id = formdata.get("admissionid");

  if (!syllabus && !session && !name && !guardian && !category && !admission_id) {
    alert("Please Enter Atleast One Input");
    return;
  }
  const param = new URLSearchParams({
    fetch: 1,
    syllabus: syllabus,
    session: session,
    name: name,
    guardian: guardian,
    category: category,
    admission_id :admission_id
  });
  search();
  /* Search */
  async function search() {
    try {
      const response = await fetch(`search.php?${param}`);

      if (!response.ok) {
        alert("There is a problem !!");
      }

      studentdata = await response.json();
      console.log(studentdata);

      if (studentdata.length == 0) {
        alert("No data Found !!");
        return;
      }
      print.style.display = "flex";
      show();
    } catch (error) {
      console.log(error);
    }
  }

  function show() {
    const printmain = document.querySelector('.printmain');
    let tiles = printmain.querySelector('.tiles_search');

      if (!tiles) {
        tiles = document.createElement('div');
        tiles.className = 'tiles_search';
        printmain.appendChild(tiles);
    }

    tiles.replaceChildren();
    studentdata.forEach((student) => {
      const tile = document.createElement("div");
      tile.className = "tile_search";

      const admission_id = document.createElement("p");
      admission_id.textContent =
        "Admission Id :" + (student.admission_id ?? "N/A");

      const name = document.createElement("p");
      name.textContent = "Name :" + (student.candidate_name ?? "N/A");

      const branch = document.createElement("p");
      branch.textContent = "Branch :" + (student.syllabus ?? "N/A");

      const session = document.createElement("p");
      session.textContent =
        "Session :" + (student.academic_session ?? "N/A");

      tile.append(admission_id, name, branch, session);

      tiles.appendChild(tile);
    });
    printmain.append(tiles);
  }
});

crossbtn.addEventListener("click", () => {
  print.style.display = "none";
});

function pdf(){
    const container_pdf = document.createElement('table');
    screen.replaceChildren();
    container_pdf.replaceChildren();
    try{
        container_pdf.innerHTML = `
        <caption align='center'>
            Mahamaya polytechnic of information technology , Hathras
        </caption>
            <tr>
            <th>Admission ID</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Session</th>
            </tr>`;
        studentdata.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${student.admission_id}</td>
            <td>${student.candidate_name}</td>
            <td>${student.syllabus}</td>
            <td>${student.academic_session}</td>
            `;
            container_pdf.appendChild(row);
        })
    }
    catch (error){
        console.log(error);
    }

    if(container_pdf){
        screen.replaceChildren(container_pdf);
        screen.style.display = 'flex';
        window.print();
        screen.replaceChildren();
        screen.style.display = 'none';
    }
}

/* Print excel */

function excel() {
const data = [
["Admission ID", "Name", "Branch", "Session","Guardian","DOB","Permanent Address","Mailing Address","phone1","phone2","Category","Other","Previous school"]
];

studentdata.forEach(student => {
    data.push([
        student.admission_id ?? "",
        student.candidate_name ?? "",
        student.syllabus ?? "",
        student.academic_session ?? "",
        student.guardian_name ?? "",
        student.dob ?? "",
        student.permanent_address ?? "",
        student.mailing_address ?? "",
        student.contact1 ?? "",
        student.contact2 ?? "",
        student.category ?? "",
        student.other_category ?? "",
        student.previous_school ?? ""
    ]);
});

let csvContent = "data:text/csv;charset=utf-8,\uFEFF";

data.forEach(function(rowArray) {
    let row = rowArray.map(value =>
        `"${String(value).replace(/"/g, '""')}"`
    ).join(",");

    csvContent += row + "\r\n";
});

const encodedUri = encodeURI(csvContent);
const link = document.createElement("a");

link.setAttribute("href", encodedUri);
link.setAttribute("download", "student_data.csv");

document.body.appendChild(link);
link.click();
document.body.removeChild(link);

}
let offset = 0;
const limit = 20;
let isLoading = false;
let hasMore = true;

const container = document.getElementById("studentsContainer");
const loading = document.getElementById("loading");
const branchfilter = document.getElementById("branch");
const sessionfilter = document.getElementById("session");
const screen = document.querySelector('.screen');
const session_info = new Set();
let students;


/* fetch student */
async function fetchStudents() {
  if (isLoading || !hasMore) {
    return;
  }

  isLoading = true;
  loading.textContent = "Loading students...";

  const param = new URLSearchParams({
    fetch: 1,
    branch: branchfilter.value,
    session: sessionfilter.value,
    offset: offset,
  });
  try {
    /* API fetch */
    const response = await fetch(`maya.php?${param}`);

    if (!response.ok) {
      console.log("There is a problem !!");
    }

    students = await response.json();

    /* student card */
    students.forEach((student) => {
      const tile = document.createElement("div");
      tile.className = "alltile";

      const admissionId = document.createElement("p");
      admissionId.textContent =
        "Admission ID : " + (student.admission_id ?? "N/A");

      const name = document.createElement("p");
      name.textContent = "Student Name : " + (student.candidate_name ?? "N/A");

      const branch = document.createElement("p");
      branch.textContent = "Branch : " + (student.syllabus ?? "N/A");

      const session = document.createElement("p");
      session.textContent =
        "Admission Session : " + (student.academic_session ?? "N/A");

      tile.append(admissionId, name, branch, session);
      container.appendChild(tile);

      const academic_session = student.academic_session;
      if (academic_session && !session_info.has(academic_session)) {
        session_info.add(academic_session);
        const session_dropdown = document.createElement("option");
        session_dropdown.setAttribute("value", academic_session);
        session_dropdown.innerText = academic_session;

        sessionfilter.appendChild(session_dropdown);
      }

    });
    offset += students.length;

    if (students.length < limit) {
      hasMore = false;
    }

    loading.textContent = hasMore
      ? "Scroll down to load more students"
      : "All students loaded";
  } catch (error) {
    console.error(error);
    loading.textContent = "There is a problem . Try again ";
  } finally {
    isLoading = false;
  }
}

fetchStudents();

/* print in Pdf */
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
        students.forEach(student => {
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

students.forEach(student => {
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


window.addEventListener("scroll", () => {
  const nearBottom =
    window.innerHeight + window.scrollY >=
    document.documentElement.scrollHeight - 200;

  if (nearBottom) {
    fetchStudents();
  }
});

/* filter */

branchfilter.addEventListener("change", applyfilter);
sessionfilter.addEventListener("change", applyfilter);

function applyfilter() {
  offset = 0;
  hasMore = true;
  container.replaceChildren();
  fetchStudents();
}

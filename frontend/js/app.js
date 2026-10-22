/* ============================================================
   app.js – Lab Work 4
   Connects the frontend to the PHP backend using fetch() (AJAX)
   ============================================================ */

/* ---------------- Page 2: submit the form ---------------- */
const form = document.getElementById('studentForm');

if (form) {
    const msg = document.getElementById('formMessage');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();                    // stop the page reload

        const formData = new FormData(form);   // collects all inputs

        try {
            const response = await fetch('../backend/submit.php', {
                method: 'POST',                // same as method="post"
                body: formData
            });

            const result = await response.json();

            msg.textContent = result.message;
            msg.className = 'form-message ' + (result.ok ? 'success' : 'error');

            if (result.ok) form.reset();       // clear the form on success
        } catch (err) {
            msg.textContent = 'Could not reach the server. Is PHP running?';
            msg.className = 'form-message error';
        }
    });
}

/* ---------------- Page 3: load the table ----------------- */
const tableBody = document.querySelector('#studentTable tbody');

if (tableBody) {
    fetch('../backend/get_students.php')
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = '';          // remove "Loading..." row

            if (!data.ok || data.count === 0) {
                tableBody.innerHTML =
                    '<tr><td colspan="7" class="loading">No students found. Add some using the Form page.</td></tr>';
                return;
            }

            data.students.forEach((s, i) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${s.enrollment}</td>
                    <td>${s.name}</td>
                    <td>${s.course}</td>
                    <td>${s.gender}</td>
                    <td>${s.marks}</td>
                    <td class="${gradeClass(s.grade)}">${s.grade}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(() => {
            tableBody.innerHTML =
                '<tr><td colspan="7" class="loading">Error loading data from the server.</td></tr>';
        });
}

/* Helper: pick a colour class for each grade */
function gradeClass(grade) {
    if (grade.startsWith('A')) return 'grade-a';
    if (grade === 'B' || grade === 'C') return 'grade-b';
    if (grade === 'D') return 'grade-d';
    return 'grade-f';
}
const field = document.querySelector('.field');
const studentId = new URLSearchParams(window.location.search).get('id');

async function documentfetch() {
    const params = new URLSearchParams({ id: studentId, user: '1' });

    try {
        const response = await fetch(`detail.php?${params}`);
        if (!response.ok) {
            throw new Error('Could not load the active documents.');
        }

        const documents = await response.json();
        field.replaceChildren();

        documents.forEach((item) => {
            const box = document.createElement('div');
            box.className = 'box document-box';

            const name = document.createElement('span');
            name.className = 'document-name';
            name.textContent = item.document_name;

            const status = document.createElement('span');
            status.className = `document-status ${Number(item.accepted) === 1 ? 'is-accepted' : 'is-pending'}`;
            status.textContent = Number(item.accepted) === 1 ? 'Accepted' : 'Not accepted';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = `document-toggle ${Number(item.accepted) === 1 ? 'is-accepted' : ''}`;
            button.dataset.documentId = item.document_id;
            button.dataset.accepted = Number(item.accepted) === 1 ? '0' : '1';
            button.textContent = Number(item.accepted) === 1 ? 'Undo acceptance' : 'Accept';

            box.append(name, status, button);
            field.appendChild(box);
        });
    } catch (error) {
        console.error(error);
        field.textContent = 'Could not load documents. Please try again.';
    }
}

field.addEventListener('click', async (event) => {
    const button = event.target.closest('.document-toggle');
    if (!button) {
        return;
    }

    button.disabled = true;
    const formData = new FormData();
    formData.set('action', 'toggle_document');
    formData.set('student_id', studentId);
    formData.set('document_id', button.dataset.documentId);
    formData.set('accepted', button.dataset.accepted);

    try {
        const response = await fetch('detail.php', {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (!response.ok || result.status !== 'done') {
            throw new Error(result.message || 'Could not update document status.');
        }
        await documentfetch();
    } catch (error) {
        console.error(error);
        alert(error.message || 'Could not update document status.');
        button.disabled = false;
    }
});

documentfetch();
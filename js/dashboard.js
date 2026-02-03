document.addEventListener('DOMContentLoaded', async () => {
	const container = document.getElementById('systemsContainer');

	try {
		const res = await fetch('../api/assignment/get_dashboard_systems.php');
		const result = await res.json();

		if (result.status !== 'success') {
			container.innerHTML = `<p class="text-danger text-center mt-5">${result.message || 'Failed to load systems.'}</p>`;
			return;
		}

		const systems = result.data;

		if (!systems || systems.length === 0) {
			container.innerHTML = '<p class="text-muted text-center mt-5">No assigned systems found.</p>';
			return;
		}

		container.innerHTML = ''; // Clear container
		const fragment = document.createDocumentFragment();

		systems.forEach(sys => {
			const div = document.createElement('div');
			div.className = 'col-md-4 mb-4';
			div.innerHTML = `
                <div class="card shadow-sm border-0 h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-3">${sys.system_name}</h5>
                        <p class="text-secondary">Role: ${sys.role}</p>
                        <button class="btn btn-danger" onclick="redirectToSystem('${sys.domain_url}')">
                            Open ${sys.system_name}
                        </button>
                    </div>
                </div>
            `;
			fragment.appendChild(div);
		});

		container.appendChild(fragment);

	} catch (err) {
		console.error('Error fetching systems:', err);
		container.innerHTML = '<p class="text-danger text-center mt-5">Failed to load systems.</p>';
	}
});

// Redirect to system via PHP SSO handler
function redirectToSystem(domainUrl) {
	if (!domainUrl) return;
	window.location.href = `../api/assignment/redirect_to_system.php?domain_url=${encodeURIComponent(domainUrl)}`;
}

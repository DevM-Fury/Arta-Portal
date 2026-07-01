	<!-- Footer -->
	<div class="footer">
		<div>ARTA Government System v2.1 | Secure Access Portal</div>
		<!-- <div class="binary-code">1010101010101010101 0101010101010101010</div> -->
	</div>

	<script>
	if (!window.__artaLogoutConfirmBound) {
		window.__artaLogoutConfirmBound = true;

		document.addEventListener('click', function(event) {
			const logoutLink = event.target.closest('a[href="logout.php"], a[href$="/logout.php"]');

			if (!logoutLink) {
				return;
			}

			event.preventDefault();

			if (window.Swal) {
				Swal.fire({
					title: 'Logout?',
					text: 'Do you want to log out of your account?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes, log out',
					cancelButtonText: 'Cancel',
					confirmButtonColor: '#8B0000'
				}).then(function(result) {
					if (result.isConfirmed) {
						window.location.href = logoutLink.href;
					}
				});
				return;
			}

			if (window.confirm('Do you want to log out of your account?')) {
				window.location.href = logoutLink.href;
			}
		});
	}
	</script>

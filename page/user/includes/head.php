<head>
	<meta charset="UTF-8">
	<title>ARTA E-KYC Dashboard</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<style>
	:root {
		--arta-red: #8B0000;
		--arta-light: #fff8e1;
		--arta-gray: #f7f7f7;
		--arta-blue1: #23395d;
		--arta-blue2: #1e2a3a;
		--arta-blue3: #0f2027;
		--arta-blue-gradient: linear-gradient(180deg, #23395d 0%, #1e2a3a 60%, #0f2027 100%);
	}

	body {
		background: linear-gradient(120deg, var(--arta-gray) 60%, var(--arta-light) 100%);
		font-family: 'Poppins', sans-serif;
	}

	.header-bar {
		background: linear-gradient(90deg, #23395d 0%, #271d5dff 60%, #150e3dff 100%);
		color: #fff;
		padding: 0.8rem 2rem;
		position: fixed;
		top: 10px;
		/* dati 0, tinaasan para bumaba */
		left: 230px;
		right: 20px;
		z-index: 100;
		display: flex;
		align-items: center;
		justify-content: space-between;
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
		border-radius: 24px;
		border: 2px solid #23395d;
	}


	.header-title {
		font-size: 2.2rem;
		font-weight: 500;
		letter-spacing: 2px;
		display: flex;
		align-items: center;
		gap: 18px;
		text-shadow: 0 2px 12px rgba(139, 0, 0, 0.12);
	}

	.header-title i {
		font-size: 2.5rem;
		color: #f9fafcff;
		filter: drop-shadow(0 2px 6px #1c1c1bff);
	}

	.header-user {
		font-size: 1.2rem;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 12px;
		background: rgba(2, 2, 41, 1);
		padding: 10px 24px;
		border-radius: 30px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.10);
		backdrop-filter: blur(2px);
	}

	.header-user i {
		font-size: 1.7rem;
		color: #f0ededff;
	}

	.sidebar {
		background: var(--arta-blue-gradient);
		color: #fff;
		min-height: 100vh;
		padding-top: 2rem;
		position: fixed;
		left: 0;
		top: 0;
		width: 220px;
		box-shadow: 2px 0 24px rgba(0, 0, 0, 0.18);
	}

	.sidebar .nav-link {
		color: #fff !important;
		font-weight: 500;
		padding: 14px 26px;
		border-radius: 12px;
		margin-bottom: 12px;
		transition: background .2s, color .2s;
		font-size: 1.1rem;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.sidebar .nav-link.active,
	.sidebar .nav-link:hover {
		background: #fff;
		color: #23395d !important;
	}

	.sidebar .sidebar-title {
		font-size: 1.5rem;
		font-weight: 700;
		margin-bottom: 2.5rem;
		text-align: center;
		letter-spacing: 1px;
		text-shadow: 0 2px 8px #0b0b0aff;
	}

	.main-content {
		margin-left: 240px;
		padding: 7rem 1rem 2rem 1rem;
	}

	.card {
		border-radius: 22px;
		box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
		border: none;
		background: #fff;
		transition: box-shadow .2s, transform .2s;
	}

	.card:hover {
		box-shadow: 0 16px 48px rgba(35, 57, 93, 0.18);
		transform: translateY(-4px) scale(1.03);
	}

	.card-title {
		color: #23395d;
		font-weight: 700;
		letter-spacing: 1px;
	}

	.card-icon {
		font-size: 3rem;
		color: #23395d;
		margin-bottom: 10px;
		filter: drop-shadow(0 2px 6px #fff8e1);
	}

	.list-group-item {
		background: transparent;
		border: none;
		font-size: 1.08rem;
	}

	@media (max-width: 991px) {
		.sidebar {
			position: static;
			width: 100%;
			min-height: auto;
			padding-top: 1rem;
		}

		.header-bar {
			left: 0;
			padding: 1rem;
			border-radius: 0;
		}

		.main-content {
			margin-left: 0;
			padding: 8rem 1rem 2rem 1rem;
		}
	}
	</style>
</head>
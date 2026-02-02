<head>
	<meta charset="UTF-8">
	<title>ARTA PORTAL Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap JS (required for modal) -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

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
	}

	/* ==============================
   MOBILE-FIRST / FLUID STYLES
============================== */
	body {
		background: linear-gradient(120deg, var(--arta-gray) 60%, var(--arta-light) 100%);
		font-family: 'Poppins', sans-serif;
		margin: 0;
		padding: 0;
	}

	/* HEADER */
	.header-bar {
		background: linear-gradient(90deg, #23395d 0%, #271d5dff 60%, #150e3dff 100%);
		color: #fff;
		padding: clamp(0.4rem, 1vw, 0.8rem) clamp(0.8rem, 2vw, 2rem);
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		display: flex;
		flex-direction: row;
		/* single row mobile */
		justify-content: space-between;
		align-items: center;
		flex-wrap: nowrap;
		z-index: 100;
	}

	.header-title {
		font-size: clamp(0.9rem, 2vw, 1.2rem);
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: clamp(6px, 1vw, 18px);
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.header-title i {
		font-size: clamp(1rem, 2.5vw, 1.5rem);
		color: #f9fafc;
	}

	/* USER ICON + DROPDOWN */
	.header-user {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		font-size: clamp(0.85rem, 1.8vw, 1.2rem);
		padding: clamp(5px, 1vw, 12px) clamp(10px, 2vw, 24px);
		background: rgba(2, 2, 41, 1);
		border-radius: clamp(6px, 1vw, 10px);
		flex-wrap: nowrap;
	}

	.header-user i {
		font-size: clamp(1rem, 2.5vw, 1.5rem);
		color: #f0eded;
		cursor: pointer;
	}

	.header-user span {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	/* Mobile: collapse username & logout */
	@media (max-width: 991px) {

		.header-user span,
		.header-user a {
			display: none;
		}
	}

	/* Extra small mobile: compact user */
	@media (max-width: 400px) {
		.header-user {
			font-size: 0.45rem;
			padding: 2px 6px;
			gap: 2px;
		}

		.header-user i {
			font-size: 1.2rem;
		}
	}

	/* MAIN CONTENT */
	.main-content {
		margin: 0 auto;
		padding-top: clamp(5rem, 10vw, 7rem);
		padding-left: clamp(0.8rem, 3vw, 2rem);
		padding-right: clamp(0.8rem, 3vw, 2rem);
		padding-bottom: 2rem;
	}

	/* CARDS
	.card-container {
		display: flex;
		flex-wrap: wrap;
		gap: clamp(0.5rem, 2vw, 1rem);
		justify-content: center;
	}

	.card-container .card {
		flex: 1 1 clamp(220px, 45%, 400px);
		border-radius: clamp(12px, 2vw, 22px);
		box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
		transition: box-shadow .2s, transform .2s;
		max-width: 400px;
	}

	.card-container .card:hover {
		box-shadow: 0 12px 36px rgba(35, 57, 93, 0.18);
		transform: translateY(-3px) scale(1.02);
	}

	.card-title {
		font-size: clamp(0.95rem, 1.8vw, 1.2rem);
		color: #23395d;
		font-weight: 700;
	}

	.card-icon {
		font-size: clamp(2rem, 4vw, 3rem);
		color: #23395d;
		margin-bottom: 8px;
	} */


	/* Card Container */
	.card-container {
		display: flex;
		flex-wrap: wrap;
		gap: 1rem;
		justify-content: center;
	}

	/* Individual Card */
	.card {
		flex: 1 1 calc(33.333% - 1rem);
		/* 3 per row desktop */
		max-width: 300px;
		min-width: 250px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		/* vertical center */
		align-items: center;
		/* horizontal center */
		border-radius: 15px;
		box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
		transition: transform 0.2s, box-shadow 0.2s;
		padding: 1.5rem;
	}

	/* Hover Effect */
	.card:hover {
		transform: translateY(-3px) scale(1.02);
		box-shadow: 0 12px 36px rgba(35, 57, 93, 0.18);
	}

	/* Card Icon */
	.card-icon {
		font-size: 2.5rem;
		color: #23395d;
		margin-bottom: 8px;
	}

	/* Card Title */
	.card-title {
		font-size: 1rem;
		font-weight: 700;
		color: #23395d;
		margin-bottom: 5px;
	}

	/* Card Value */
	.card p.display-6 {
		font-size: 2rem;
		font-weight: 600;
		margin: 0;
	}

	/* Responsive Adjustments */

	/* Tablets */
	@media (max-width: 991px) {
		.card {
			flex: 1 1 calc(50% - 1rem);
			/* 2 per row */
		}
	}

	/* Mobile */
	@media (max-width: 576px) {
		.card {
			flex: 1 1 100%;
			/* 1 per row */
		}
	}


	/* ==============================
   DESKTOP / LAPTOP FIXED LAYOUT
============================== */
	@media (min-width: 992px) {
		.header-bar {
			flex-direction: row;
			align-items: center;
			justify-content: space-between;
			padding: 0.5rem 2rem;
			left: 260px;
			right: 20px;
			top: 10px;
			border-radius: 10px;
			max-width: calc(100% - 280px);
		}

		.header-title {
			font-size: 1.2rem;
			gap: 18px;
		}

		.header-title i {
			font-size: 1.5rem;
		}

		.header-user {
			font-size: 0.5rem;
			padding: 5px 10px;
			width: auto;
			gap: 5px;
			flex-wrap: nowrap;
		}

		.main-content {
			margin-left: 240px;
			padding-top: 7rem;
			padding-left: 2rem;
			padding-right: 2rem;
		}

		.card-container .card {
			flex: 1 1 calc(25% - 1rem);
			max-width: 300px;
			min-width: 250px;
			border-radius: 22px;
		}

		.card-title {
			font-size: 1rem;
		}

		.card-icon {
			font-size: 3rem;
		}
	}


	/* ✅ FOOTER - Fixed look, but no overlap with sidebar */
	.footer {
		position: fixed;
		bottom: 0;
		left: 220px;
		/* same width as your sidebar */
		width: calc(100% - 220px);
		/* adjust width */
		background-color: #fff;
		box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
		font-size: 0.9rem;
		z-index: 1000;
		padding: 0.75rem 1rem;
		text-align: center;
	}

	/* ✅ Mobile View - sidebar collapses, so footer spans full width */
	@media (max-width: 768px) {
		.footer {
			left: 0;
			width: 100%;
			font-size: 0.8rem;
			padding: 0.75rem;
		}
	}
	</style>
</head>
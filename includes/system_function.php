<!-- STYLE -->
<style>
:root {
	--arta-red: #8B0000;
	--arta-light: #fff8e1;
	--arta-gray: #f7f7f7;
	--arta-blue1: #23395d;
	--arta-blue2: #1e2a3a;
	--arta-blue3: #0f2027;
}

body {
	background-color: var(--arta-gray);
	font-family: 'Poppins', sans-serif;
	color: #333;
}

.card {
	background: #fff;
	border-radius: 16px;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
	overflow: hidden;
}

/* Table Styles */
.table {
	width: 100%;
	min-width: 100%;
	table-layout: fixed;
	border-collapse: collapse;
	border-spacing: 0;
	background-color: #fff;
	border-radius: 8px;
	overflow: hidden;
}


.table thead {
	background: var(--arta-blue3);
	color: #fff;
	text-transform: uppercase;
}

.table thead th {
	font-size: 14px;
	letter-spacing: 0.5px;
	padding: 14px;
	border: none !important;
}

.table tbody td {
	padding: 12px;
	font-size: 14px;
	vertical-align: middle;
}

.table tbody tr:nth-child(even) {
	background-color: var(--arta-light);
}

.table tbody tr:hover {
	background-color: rgba(15, 32, 39, 0.08);
	transition: 0.2s;
}

.expandable-row {
	cursor: pointer;
}

.expandable-row.active {
	background-color: rgba(15, 32, 39, 0.1);
}

.expandable-content {
	display: none;
	background-color: #f9f9f9;
}

.expand-body {
	text-align: left;
	padding: 18px 24px;
	background: #ffffff;
	border-left: 4px solid var(--arta-blue1);
	border-radius: 8px;
	box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.05);
	font-size: 14px;
	line-height: 1.6;
	max-height: 0;
	overflow: hidden;
	transition: max-height 0.3s ease-in-out;
}

.expandable-row.active+.expandable-content .expand-body {
	max-height: 300px;
}

#searchInput {
	border-radius: 6px;
	padding: 6px 10px;
	font-size: 14px;
	width: 250px;
	transition: all 0.3s ease;
}

@media (max-width: 768px) {
	#searchInput {
		width: 100%;
		padding: 8px 12px;
	}
}


#searchInput:focus {
	border-color: var(--arta-blue1);
	box-shadow: 0 0 0 0.2rem rgba(35, 57, 93, 0.25);
}

.btn-primary {
	background-color: var(--arta-blue1);
	border: none;
}

.table thead th {
	background-color: var(--arta-blue3);
	color: #ffffff;
	border: none !important;
	padding: 14px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	font-size: 14px;
}


.btn-primary:hover {
	background-color: var(--arta-blue3);
}

h4.fw-bold {
	color: var(--arta-blue1);
}
</style>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo isset($title) && $title ? $title : "N/A"; ?></title>
    <link rel="icon" href="img/logo.ico" type="image/ico">
    <link
        href="vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
        type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .floor-block {
            border: none;
            border-radius: 1rem;
            background: #f8fafc;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            padding: 1.5rem 1.2rem;
            margin-bottom: 2rem;
            transition: box-shadow 0.2s;
        }

        .floor-block:hover {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
        }

        .floor-block h4 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .flat-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .flat {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            min-width: 0;
            max-width: 100%;
            width: auto;
            padding: 0.5rem 1.2rem;
            border-radius: 2rem;
            box-sizing: border-box;
        }

        .flat.available {
            border: 2px solid #4CAF50;
        }

        .flat.emi {
            border: 2px solid #FFC107;
        }

        .flat.sold {
            border: 2px solid #F44336;
        }

        .flat input {
            border: none;
            background: transparent;
            font-weight: 600;
            width: 32px;
            text-align: center;
            color: #333;
        }

        .flat input:focus {
            outline: none;
            background: #f1f3f6;
            border-radius: 0.5rem;
        }

        .flat select {
            border-radius: 1rem;
            border: 1px solid #e0e0e0;
            background: #f8fafc;
            padding: 2px 8px;
            font-size: 0.95rem;
        }

        .flat-label {
            padding-right: 5px;
        }


        /* Responsive: On small screens, stack label and select vertically */
        @media (max-width: 575.98px) {
            .flat {
                flex-direction: column;
                align-items: stretch;
                padding: 0.7rem 0.7rem;
            }

            .flat-label,
            .flat-select {
                margin-left: 0 !important;
                margin-top: 0.5rem;
                padding-right: 5px;
            }

            .flat-select {
                width: 100%;
                max-width: 100%;
            }
        }

        .form-select,
        .form-select:focus {
            border-radius: 2rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.8rem;
            border: 1px solid #bfc9d1;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .15);
            background: #fff;
        }

        .flat-select {
            border-radius: 2rem;
            padding: 0.1rem 0.1rem;
            font-size: 0.8rem;
            border: 1px solid #bfc9d1;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            min-width: 80px;
            max-width: 120px;
            width: auto;
            flex: 1 1 auto;
            box-sizing: border-box;
        }

        .flat-select:focus {
            border-color: #86b7fe;
            background: #fff;
            outline: none;
        }

        .container-fluid {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 2rem 1.5rem;
            margin-top: 2rem;
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 1rem 0.5rem;
            }
        }

        .form-control-floor {
            border-radius: 2rem;
            font-size: 0.8rem;
            padding: 1.3rem 3.5rem;
            border: 1px solid #bfc9d1;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control-user:floor {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .15);
            background: #fff;
        }

        .btn,
        .btn:focus,
        .btn:active {
            border-radius: 2rem !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .flat-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.7em;
            height: 1.7em;
            border-radius: 50%;
            margin-right: 0.5em;
            font-size: 1.2em;
            color: #fff;
            background: #ccc;
            transition: background 0.2s;
        }

        .flat-badge.available {
            background: #4CAF50;
        }

        .flat-badge.emi {
            background: #FFC107;
            color: #333;
        }

        .flat-badge.sold {
            background: #F44336;
        }

        /* Style the Select2 selection box to look like your .form-select */
        .select2-container--default .select2-selection--single {
            border-radius: 2rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: 1px solid #bfc9d1;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            min-height: 44px;
            height: auto;
            display: flex;
            align-items: center;
            width: 100% !important;
            max-width: 100%;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default .select2-selection--single.select2-selection--focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .15);
            background: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: normal;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }

        .select2-container {
            width: 100% !important;
            max-width: 100%;
        }

        .custom-flat-select {
            border-radius: 2rem;
            background: #fff;
            padding: 0.5rem 1.2rem;
            font-size: 1.1rem;
            min-width: 120px;
            max-width: 100%;
            height: 50px;
            color: #333;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .custom-flat-select:focus {
            border-color: #86b7fe;
            background: #cccccc5e;
        }

        @media print {

            body,
            html {
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Hide everything first */
            body * {
                visibility: hidden;
            }

            /* Show only containers */
            .container.border,
            .container.border *,
            .container.py-4,
            .container.py-4 * {
                visibility: visible;
            }

            /* Absolute positioning with top/left 0 */
            .container.border {
                position: absolute !important;
                top: 0;
                left: 0;
                right: 0;
                box-sizing: border-box;
            }

            .container.py-4 {
                position: absolute !important;
                top: calc(23%);
                left: 0;
                right: 0;
                padding: 1rem;
                box-sizing: border-box;
            }

            /* Hide form and print button */
            form[method="POST"],
            #printReportBtn {
                display: none !important;
            }
        }
    </style>
</head>
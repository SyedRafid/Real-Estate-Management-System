<?php
require_once __DIR__ . '/partials/_session.php';

$title = "Add Sale";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'partials/_sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'partials/_topbar.php'; ?>
                <!-- End of Topbar -->

                <div class="m-4">
                    <h1 class="h3 text-gray-800">Add Sale</h1>
                    <p class="mb-0">
                        This section allows you to record a new sale transaction. Provide essential details such as the buyer's information, property sold, and any associated documentation. Keeping accurate sale records ensures better financial tracking and supports transparent business operations.
                    </p>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12 col-sm-6 col-md-6 mb-3 mb-md-0 d-flex flex-column">
                            <label for="buildingSelect" class="form-label">Select Building:</label>
                            <select id="buildingSelect" class="form-select">
                                <option value="" selected disabled>Select a building...</option>
                            </select>
                        </div>
                    </div>
                    <div id="layoutContainer" class="mb-4"></div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'partials/_footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Logout Modal-->
    <?php include 'partials/_logoutModal.php'; ?>
    <?php include 'partials/_scripts.php'; ?>

    <script>
        let layoutData = {};

        $(document).ready(function() {
            // Initialize Select2
            $('#buildingSelect').select2({
                placeholder: "Select a building...",
                width: '100%'
            });

            // Fetch building list
            fetch('get_buildings.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const $select = $('#buildingSelect');
                        data.buildings.forEach(b => {
                            $select.append(new Option(b.building_name, b.building_id));
                        });
                        $select.trigger('change.select2'); // Refresh
                    }
                });

            // Handle building selection change
            $('#buildingSelect').on('change', function() {
                const buildingId = $(this).val();
                if (!buildingId) return;

                fetch('get_building_layout.php?building_id=' + encodeURIComponent(buildingId))
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Object.keys(layoutData).forEach(k => delete layoutData[k]);
                            $('#layoutContainer').empty();

                            Object.entries(data.layoutData).forEach(([floor, flats]) => {
                                layoutData[floor] = flats;
                                renderFloor(floor, flats);
                            });
                            updateCopyDropdowns?.();
                        } else {
                            Swal.fire("Error", data.message || "Failed to fetch layout.", "error");
                        }
                    });
            });
        });

        // Helper to render a floor 
        function renderFloor(floor, flats) {
            const floorBlock = document.createElement('div');
            floorBlock.className = 'floor-block';
            floorBlock.id = 'floor-' + floor;
            const title = document.createElement('h4');
            title.innerText = 'Floor ' + floor;
            floorBlock.appendChild(title);

            const flatRow = document.createElement('div');
            flatRow.className = 'flat-row';

            flats.forEach((flat, i) => {
                // Normalize status from DB: trim and lowercase
                const status = (flat.status || '').trim().toLowerCase();

                const flatWrapper = document.createElement('div');
                flatWrapper.className = 'flat ' + status;

                // Status badge
                const statusBadge = document.createElement('span');
                statusBadge.className = 'flat-badge ' + status;
                statusBadge.title = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.innerHTML = {
                    available: '<i class="bi bi-check-circle-fill"></i>',
                    emi: '<i class="bi bi-cash-coin"></i>',
                    sold: '<i class="bi bi-x-circle-fill"></i>'
                } [status] || '';

                // Flat label
                const prefixSpan = document.createElement('span');
                prefixSpan.textContent = flat.label;
                prefixSpan.style.fontWeight = 'bold';
                prefixSpan.style.marginRight = '0.25rem';

                // Status select
                const statusSelect = document.createElement('select');
                statusSelect.className = 'flat-select';
                statusSelect.disabled = true;

                ['available', 'emi', 'sold'].forEach(optStatus => {
                    const opt = document.createElement('option');
                    opt.value = optStatus;
                    opt.text = optStatus.charAt(0).toUpperCase() + optStatus.slice(1);
                    if (optStatus === status) opt.selected = true;
                    statusSelect.appendChild(opt);
                });

                // Click handler: only allow if available, else show Swal
                flatWrapper.style.cursor = 'pointer';
                flatWrapper.addEventListener('click', function() {
                    if (status === 'available') {
                        window.location.href = 'addSaleInfo.php?flat_id=' + flat.flat_id;
                    } else {
                        let statusMsg = '';
                        if (status === 'emi') {
                            statusMsg = 'This flat is currently on EMI.';
                        } else if (status === 'sold') {
                            statusMsg = 'This flat is already Sold.';
                        } else {
                            statusMsg = 'This flat is not available.';
                        }
                        Swal.fire({
                            icon: 'warning',
                            title: 'Not Available',
                            text: statusMsg,
                        });
                    }
                });

                flatWrapper.appendChild(statusBadge);
                flatWrapper.appendChild(prefixSpan);
                flatWrapper.appendChild(statusSelect);
                flatRow.appendChild(flatWrapper);
            });

            floorBlock.appendChild(flatRow);
            document.getElementById('layoutContainer').appendChild(floorBlock);
        }
    </script>
</body>

</html>
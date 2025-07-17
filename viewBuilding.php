<?php
require_once __DIR__ . '/partials/_session.php';

$buildingId = $_GET['id'] ?? null;
$showRedirectAlert = false;

if (!$buildingId) {
    $showRedirectAlert = true;
}

$title = "View Building";
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
                    <h1 class="h3 text-gray-800">View Building Layout</h1>
                    <p class="mb-0">
                        This section displays the layout of a selected building. You can visually inspect unit distribution, available spaces, and overall floor plans. Use this overview to better understand the building's structure and assist in property management decisions.
                    </p>
                </div>


                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12 col-sm-6 col-md-6 mb-3 mb-md-0 d-flex flex-column">
                            <label for="buildingSelect" class="form-label">Select Building:</label>
                            <select id="buildingSelect" class="form-select" disabled>
                                <option value="" <?= !$buildingId ? 'selected' : '' ?> disabled>Select a building...</option>
                                <?php
                                $stmt = $dbh->query("SELECT building_id, building_name FROM buildings ORDER BY building_name");
                                $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($buildings as $building) {
                                    $selected = ($buildingId == $building['building_id']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($building['building_id']) . '" ' . $selected . '>' . htmlspecialchars($building['building_name']) . '</option>';
                                }
                                ?>
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
            const preselectedId = "<?= $buildingId ?>";

            // Initialize Select2
            $('#buildingSelect').select2({
                placeholder: "Select a building...",
                width: '100%'
            });

            // If there's a pre-selected building, set it and fetch layout immediately
            if (preselectedId) {
                $('#buildingSelect').val(preselectedId).trigger('change.select2');
                fetchBuildingLayout(preselectedId);
            }

            // Fetch and render layout function
            function fetchBuildingLayout(buildingId) {
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

                            if (typeof updateCopyDropdowns === 'function') {
                                updateCopyDropdowns();
                            }
                        } else {
                            Swal.fire("Error", data.message || "Failed to fetch layout.", "error");
                        }
                    });
            }
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
                const status = (flat.status || '').trim().toLowerCase();

                const flatWrapper = document.createElement('div');
                flatWrapper.className = 'flat ' + status;

                const statusBadge = document.createElement('span');
                statusBadge.className = 'flat-badge ' + status;
                statusBadge.title = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.innerHTML = {
                    available: '<i class="bi bi-check-circle-fill"></i>',
                    emi: '<i class="bi bi-cash-coin"></i>',
                    sold: '<i class="bi bi-x-circle-fill"></i>'
                } [status] || '';

                const prefixSpan = document.createElement('span');
                prefixSpan.textContent = flat.label;
                prefixSpan.style.fontWeight = 'bold';
                prefixSpan.style.marginRight = '0.25rem';

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

                flatWrapper.style.cursor = 'pointer';
                flatWrapper.appendChild(statusBadge);
                flatWrapper.appendChild(prefixSpan);
                flatWrapper.appendChild(statusSelect);
                flatRow.appendChild(flatWrapper);
            });

            floorBlock.appendChild(flatRow);
            document.getElementById('layoutContainer').appendChild(floorBlock);
        }
    </script>
    <?php if ($showRedirectAlert): ?>
        <script>
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Building Selected',
                    text: 'Please select a building first.',
                    confirmButtonText: 'Go to Building Manager'
                }).then(() => {
                    window.location.href = 'manageBuilding.php';
                });
            });
        </script>
    <?php exit;
    endif; ?>
</body>

</html>
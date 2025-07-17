<?php
require_once __DIR__ . '/partials/_session.php';

$title = "Add Building";
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
                <!-- Begin Page Content -->
                <div class="m-4">
                    <h1 class="h3 text-gray-800">🏢 Add Building Layout Design</h1>
                    <p class="mb-0">
                        This section allows you to upload and manage building layout designs. Provide relevant details and design files that represent the structure, dimensions, or architectural plans. Maintaining accurate and organized layout records helps ensure better planning and project execution.
                    </p>
                </div>
                <div class="container-fluid">
                    <!-- Building Info Form -->
                    <h1 class="h3 mb-4 text-gray-800">Building Details:</h1>
                    <form class="user mb-4" onsubmit="event.preventDefault(); saveLayout();">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" class="form-control form-control-user" id="buildingName" name="buildingName" placeholder="Building Name">
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" class="form-control form-control-user" id="buildingAddress" name="buildingAddress" placeholder="Building Address">
                            </div>
                        </div>
                    </form>
                    <hr class="my-3">

                    <!-- Add Floor Form -->
                    <h1 class="h3 mb-4 text-gray-800">Building Layout Design:</h1>
                    <form class="row g-3 align-items-end justify-content-center mt-4 mb-4" onsubmit="event.preventDefault(); addFloor();">
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0">
                            <input type="number" class="form-control form-control-floor" id="floorNumber" min="1" placeholder="Floor Number (e.g. 1)" />
                        </div>
                        <div class=" col-12 col-sm-6 col-md-auto mb-3 mb-md-0">
                            <input type="number" class="form-control form-control-floor" id="flatCount" min="1" placeholder="Number of Flats (e.g. 4)" />
                        </div>
                        <div class=" col-12 col-md-auto">
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Add Floor</button>
                        </div>
                    </form>
                    <hr class="my-3">

                    <!-- Copy Layout Controls -->
                    <div class="row g-3 justify-content-center mt-4 mb-4">
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                                <label for="copyFrom" class="form-label mb-1">Copy layout from Floor</label>
                                <select id="copyFrom" class="form-select">
                                    <option value="" selected disabled>Copy from</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                                <label class="form-label mb-1" style="line-height: 1.5;"><i class="fas fa-long-arrow-alt-right"></i></label>
                                <button class="btn btn-secondary w-100 mt-1" onclick="copyToNextFloor()"><i class="bi bi-arrow-down"></i> Copy to Next Floor</button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;"> <label for="copyTo" class="form-label mb-1">Copy to Floor</label>
                                <select id="copyTo" class="form-select">
                                    <option value="" selected disabled>To Floor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0 d-flex flex-column">
                            <label class="form-label mb-1" style="visibility:hidden;">Copy Layout</label>
                            <button class="btn btn-primary w-100" onclick="copyLayout()"><i class="bi bi-files"></i> Copy Layout</button>
                        </div>
                    </div>
                    <hr class="my-3">

                    <!-- Delete Floor Controls -->
                    <div class="row g-3 align-items-end justify-content-center mt-4 mb-4">
                        <div class="col-12 col-sm-6 col-md-auto mb-3 mb-md-0">
                            <select id="deleteFloor" class="form-select">
                                <option value="" selected disabled>Delete Floor</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-auto">
                            <button class="btn btn-danger w-100" onclick="deleteFloor()"><i class="bi bi-trash"></i> Delete Floor</button>
                        </div>
                    </div>
                    <hr class="my-3">

                    <!-- Export and Save Buttons -->
                    <div class="row align-items-end justify-content-center mt-4 mb-4">
                        <div class="col-12 col-md-auto">
                            <button class="btn btn-primary w-100" onclick="saveLayout()"><i class="bi bi-save"></i> Save Layout</button>
                        </div>
                    </div>
                    <hr class="my-3">

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
        const layoutData = {};

        function addFloor() {
            let floor = document.getElementById('floorNumber').value;
            const flats = document.getElementById('flatCount').value;

            if (!floor || !flats) {
                Swal.fire("Error", "Please enter floor number and number of flats.", "error");
                return;
            }

            floor = floor.toString();
            const floorNum = parseInt(floor);

            // Check for missing previous floors (enforce sequential addition)
            if (floorNum > 1) {
                for (let i = 1; i < floorNum; i++) {
                    if (!layoutData[i.toString()]) {
                        Swal.fire(
                            "Warning",
                            `Please add Floor ${i} before adding Floor ${floorNum}.`,
                            "warning"
                        );
                        return;
                    }
                }
            }

            if (layoutData[floor]) {
                Swal.fire("Error", "This floor already exists.", "error");
                return;
            }

            const floorBlock = document.createElement('div');
            floorBlock.className = 'floor-block';
            floorBlock.id = 'floor-' + floor;
            const title = document.createElement('h4');
            title.innerText = 'Floor ' + floor;
            floorBlock.appendChild(title);

            const flatRow = document.createElement('div');
            flatRow.className = 'flat-row';

            layoutData[floor] = [];

            for (let i = 0; i < flats; i++) {
                const flatWrapper = document.createElement('div');
                flatWrapper.className = 'flat available';

                // Create a label prefix span 
                const prefixSpan = document.createElement('span');
                prefixSpan.textContent = floor + String.fromCharCode(65 + i);
                prefixSpan.style.fontWeight = 'bold';
                prefixSpan.style.marginRight = '0.25rem';

                // Status select
                const statusSelect = document.createElement('select');
                statusSelect.className = 'flat-select';
                ['available', 'emi', 'sold'].forEach(status => {
                    const opt = document.createElement('option');
                    opt.value = status;
                    opt.text = status.charAt(0).toUpperCase() + status.slice(1);
                    statusSelect.appendChild(opt);
                });

                statusSelect.onchange = () => {
                    flatWrapper.className = 'flat ' + statusSelect.value;
                    layoutData[floor][i].status = statusSelect.value;
                };

                // Store the label
                layoutData[floor].push({
                    label: prefixSpan.textContent,
                    status: 'available'
                });

                flatWrapper.appendChild(prefixSpan);
                flatWrapper.appendChild(statusSelect);
                flatRow.appendChild(flatWrapper);
            }

            floorBlock.appendChild(flatRow);
            document.getElementById('layoutContainer').appendChild(floorBlock);

            // Reset input fields after adding a floor
            document.getElementById('floorNumber').value = '';
            document.getElementById('flatCount').value = '';

            updateCopyDropdowns();
        }

        function updateCopyDropdowns() {
            const copyFrom = document.getElementById('copyFrom');
            const copyTo = document.getElementById('copyTo');
            const deleteFloor = document.getElementById('deleteFloor');
            copyFrom.innerHTML = '';
            copyTo.innerHTML = '';
            deleteFloor.innerHTML = '';

            Object.keys(layoutData).sort((a, b) => a - b).forEach(floor => {
                const opt1 = document.createElement('option');
                opt1.value = floor;
                opt1.text = 'Floor ' + floor;
                const opt2 = opt1.cloneNode(true);
                const opt3 = opt1.cloneNode(true);
                copyFrom.appendChild(opt1);
                copyTo.appendChild(opt2);
                deleteFloor.appendChild(opt3);
            });
        }

        function copyLayout() {
            const from = document.getElementById('copyFrom').value;
            const to = document.getElementById('copyTo').value;

            if (!layoutData[from] || !layoutData[to]) {
                Swal.fire("Error", "Invalid floor selection.", "error");
                return;
            }

            layoutData[to] = layoutData[from].map(flat => ({
                ...flat
            }));

            // Update UI
            const floorDiv = document.getElementById('floor-' + to);
            const flatRow = floorDiv.querySelector('.flat-row');
            flatRow.innerHTML = '';

            layoutData[to].forEach((flat, i) => {
                const flatWrapper = document.createElement('div');
                flatWrapper.className = 'flat ' + flat.status;

                // Update label to match the new floor and letter
                const newLabel = to + String.fromCharCode(65 + i);
                flat.label = newLabel;

                const prefixSpan = document.createElement('span');
                prefixSpan.textContent = newLabel;
                prefixSpan.style.fontWeight = 'bold';
                prefixSpan.style.marginRight = '0.25rem';

                const statusSelect = document.createElement('select');
                ['available', 'emi', 'sold'].forEach(status => {
                    const opt = document.createElement('option');
                    opt.value = status;
                    opt.text = status.charAt(0).toUpperCase() + status.slice(1);
                    if (status === flat.status) opt.selected = true;
                    statusSelect.appendChild(opt);
                });

                statusSelect.onchange = () => {
                    flatWrapper.className = 'flat ' + statusSelect.value;
                    layoutData[to][i].status = statusSelect.value;
                };

                flatWrapper.appendChild(prefixSpan);
                flatWrapper.appendChild(statusSelect);
                flatRow.appendChild(flatWrapper);
            });
        }

        function copyToNextFloor() {
            const from = document.getElementById('copyFrom').value;
            if (!from) {
                Swal.fire("Error", "Please select a floor to copy from.", "error");
                return;
            }
            // Find the next higher floor number
            const allFloors = Object.keys(layoutData).map(Number).sort((a, b) => a - b);
            const fromNum = parseInt(from);
            let nextFloorNum = fromNum + 1;

            // Find the next available floor number greater than fromNum
            while (allFloors.includes(nextFloorNum)) {
                nextFloorNum++;
            }
            const nextFloor = nextFloorNum.toString();

            // Copy layout to the next floor and update labels
            layoutData[nextFloor] = layoutData[from].map((flat, i) => ({
                ...flat,
                label: nextFloor + String.fromCharCode(65 + i)
            }));

            // Create UI for the new floor
            const floorBlock = document.createElement('div');
            floorBlock.className = 'floor-block';
            floorBlock.id = 'floor-' + nextFloor;
            const title = document.createElement('h4');
            title.innerText = 'Floor ' + nextFloor;
            floorBlock.appendChild(title);

            const flatRow = document.createElement('div');
            flatRow.className = 'flat-row';

            layoutData[nextFloor].forEach((flat, i) => {
                const flatWrapper = document.createElement('div');
                flatWrapper.className = 'flat ' + flat.status;

                // Show the updated label as a span
                const prefixSpan = document.createElement('span');
                prefixSpan.textContent = flat.label;
                prefixSpan.style.fontWeight = 'bold';
                prefixSpan.style.marginRight = '0.25rem';

                const statusSelect = document.createElement('select');
                ['available', 'emi', 'sold'].forEach(status => {
                    const opt = document.createElement('option');
                    opt.value = status;
                    opt.text = status.charAt(0).toUpperCase() + status.slice(1);
                    if (status === flat.status) opt.selected = true;
                    statusSelect.appendChild(opt);
                });

                statusSelect.onchange = () => {
                    flatWrapper.className = 'flat ' + statusSelect.value;
                    layoutData[nextFloor][i].status = statusSelect.value;
                };

                flatWrapper.appendChild(prefixSpan);
                flatWrapper.appendChild(statusSelect);
                flatRow.appendChild(flatWrapper);
            });

            floorBlock.appendChild(flatRow);
            document.getElementById('layoutContainer').appendChild(floorBlock);
            updateCopyDropdowns();
        }

        function deleteFloor() {
            let floor = document.getElementById('deleteFloor').value;
            floor = floor.toString();

            if (!floor) {
                Swal.fire("Error", "Please select a floor to delete.", "error");
                return;
            }
            Swal.fire({
                title: `Are you sure you want to delete Floor ${floor}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (!result.isConfirmed) return;

                // Remove from data
                delete layoutData[floor];

                // Remove from UI
                const floorDiv = document.getElementById('floor-' + floor);
                if (floorDiv) floorDiv.remove();

                updateCopyDropdowns();
            });
        }

        function saveLayout() {
            const buildingName = document.getElementById('buildingName').value;
            const buildingAddress = document.getElementById('buildingAddress').value;

            // Validate building name and address
            if (!buildingName.trim()) {
                Swal.fire("Warning00", "Building name is required.", "warning");
                return;
            }
            if (!buildingAddress.trim()) {
                Swal.fire("Warning", "Building address is required.", "warning");
                return;
            }
            // Validate at least one floor exists
            if (Object.keys(layoutData).length === 0) {
                Swal.fire("Warning", "Please add at least one floor.", "warning");
                return;
            }
            // Validate each floor has at least one flat
            for (const [floor, flats] of Object.entries(layoutData)) {
                if (!Array.isArray(flats) || flats.length === 0) {
                    Swal.fire("Warning", `Floor ${floor} must have at least one flat.`, "errwarningor");
                    return;
                }
            }

            const payload = {
                buildingName,
                buildingAddress,
                layoutData
            };

            fetch('addBuilding_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: "Layout saved successfully!",
                            timer: 2000,
                        }).then(() => {
                            window.location.href = "manageBuilding.php";
                        });
                    } else {
                        Swal.fire("Error", result.message || "Failed to save layout.", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Error", "Server error. Please try again later.", "error");
                });
        }
    </script>
</body>

</html>
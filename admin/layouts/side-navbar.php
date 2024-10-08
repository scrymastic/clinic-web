

<div class="sidebar navbar">
    <div class="sidebar-header">
        <span>CLINX<strong>ME</strong></span>
    </div>
    <ul class="list-unstyled">
        <!-- Dashboard -->
        <li>
            <a href="index.php" class="rounded-tab">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <!-- Appointments Menu -->
        <li>
            <a href="appointments-view.php" class="rounded-tab">
                <i class="fas fa-calendar-alt"></i> Appointments
            </a>
        </li>
        <!-- Services Menu -->
        <li>
            <a href="#servicesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle rounded-tab">
                <i class="fas fa-concierge-bell"></i> Services
            </a>
            <ul class="collapse list-unstyled" id="servicesSubmenu">
                <li><a href="services-view.php" class="rounded-tab subtab"><i class="fas fa-eye"></i> View Services</a></li>
                <li><a href="service-add.php" class="rounded-tab subtab"><i class="fas fa-plus-circle"></i> Add Service</a></li>
            </ul>
        </li>
        <!-- Doctors Menu -->
        <li>
            <a href="#doctorsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle rounded-tab">
                <i class="fas fa-user-md"></i> Doctors
            </a>
            <ul class="collapse list-unstyled" id="doctorsSubmenu">
                <li><a href="doctors-view.php" class="rounded-tab subtab"><i class="fas fa-eye"></i> View Doctors</a></li>
                <li><a href="doctor-add.php" class="rounded-tab subtab"><i class="fas fa-plus-circle"></i> Add Doctor</a></li>
            </ul>
        </li>
        <!-- Specialties Menu -->
        <li>
            <a href="#specialtiesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle rounded-tab">
                <i class="fas fa-stethoscope"></i> Specialties
            </a>
            <ul class="collapse list-unstyled" id="specialtiesSubmenu">
                <li><a href="specialties-view.php" class="rounded-tab subtab"><i class="fas fa-eye"></i> View Specialties</a></li>
                <li><a href="specialty-add.php" class="rounded-tab subtab"><i class="fas fa-plus-circle"></i> Add Specialty</a></li>
            </ul>
        </li>
        <!-- Patients Menu -->
        <li>
            <a href="invoices-view.php" class="rounded-tab">
                <i class="fas fa-file-invoice-dollar"></i> Invoices
            </a>
        </li>
        <!-- Patients Menu -->
        <li>
            <a href="patients-view.php" class="rounded-tab">
                <i class="fas fa-user-injured"></i> Patients
            </a>
        </li>
        <!-- Admin Management -->
        <li>
            <a href="admin-management.php" class="rounded-tab">
                <i class="fas fa-user-cog"></i> Admin Management
            </a>
        </li>
        <!-- Settings -->
        <li>
            <a href="settings.php" class="rounded-tab">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
        <!-- Logout -->
        <li>
            <a href="logout.php" class="rounded-tab">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </li>
    </ul>
</div>

<!-- Add this script after the sidebar -->
<script>
// Function to save sidebar state
function saveSidebarState() {
    const openMenus = [];
    $('.collapse.show').each(function() {
        openMenus.push(this.id);
    });
    localStorage.setItem('sidebarState', JSON.stringify(openMenus));
}

// Function to load and apply sidebar state
function loadSidebarState() {
    const state = localStorage.getItem('sidebarState');
    if (state) {
        const openMenus = JSON.parse(state);
        openMenus.forEach(menuId => {
            $(`#${menuId}`).addClass('show');
            $(`[href="#${menuId}"]`).attr('aria-expanded', 'true');
        });
    }
}

// Function to highlight active page
function highlightActivePage() {
    const currentPage = window.location.pathname.split('/').pop();
    $(`.sidebar a[href="${currentPage}"]`).addClass('active');
}

$(document).ready(function() {
    // Load saved state on page load
    loadSidebarState();
    
    // Highlight the active page
    highlightActivePage();

    // Activate collapse functionality
    $('.dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        $(target).collapse('toggle');
        
        // Close other opened collapse menus (optional)
        $('.collapse').not(target).collapse('hide');
        
        // Save state after toggle
        saveSidebarState();
    });
});
</script>

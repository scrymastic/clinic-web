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
        <!-- Patients Menu -->
        <li>
            <a href="patients-view.php" class="rounded-tab">
                <i class="fas fa-user-injured"></i> Patients
            </a>
        </li>
        <!-- Profile -->
        <li>
            <a href="doctor-view.php" class="rounded-tab">
                <i class="fas fa-user"></i> Profile
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
    $(document).ready(function() {
        // Load the sidebar state from localStorage
        var activeMenu = localStorage.getItem('activeMenu');
        if (activeMenu) {
            $(activeMenu).collapse('show');  // Show the stored active menu
        }

        // Handle the collapse/expand action
        $('.dropdown-toggle').on('click', function() {
            var target = $(this).attr('href');

            // Toggle the clicked menu
            $(target).collapse('toggle');

            // Store the state of the clicked menu in localStorage
            if ($(target).hasClass('show')) {
                localStorage.setItem('activeMenu', target);
            } else {
                localStorage.removeItem('activeMenu');  // Remove if collapsed
            }

            // Optionally: Close other opened collapse menus
            $('.collapse').not(target).collapse('hide');
        });
    });
</script>

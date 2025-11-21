 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link <?= isActive('dashboard.php', 'd-flex align-items-center rounded-pill px-3 py-2 bg-primary text-white') ?> <?= isActive('dashboard.php', '', 'collapsed') ?>" href="dashboard.php">
                 <i class="bi bi-speedometer2 <?= isActive('dashboard.php', 'text-white') ?> me-2"></i>
                 <span>Dashboard</span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?= isActive('restaurantslist.php', 'd-flex align-items-center rounded-pill px-3 py-2 bg-primary text-white') ?> <?= isActive('restaurantslist.php', '', 'collapsed') ?>" href="restaurantslist.php">
                 <i class="bi bi-grid <?= isActive('restaurantslist.php', 'text-white') ?> me-2"></i>
                 <span>Restoranlar Siyahısı</span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?= isActive('settings.php', 'd-flex align-items-center rounded-pill px-3 py-2 bg-primary text-white') ?> <?= isActive('settings.php', '', 'collapsed') ?>" href="settings.php">
                 <i class="bi bi-gear <?= isActive('settings.php', 'text-white') ?> me-2"></i>
                 <span>Parametrlər</span>
             </a>
         </li>

         <!-- End Dashboard Nav -->

     </ul>

 </aside>
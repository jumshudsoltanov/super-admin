 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link <?= isActive('index.php', 'd-flex align-items-center rounded-pill px-3 py-2 bg-primary text-white') ?> <?= isActive('index.php', '', 'collapsed') ?>" href="index.php">
                 <i class="bi bi-grid <?= isActive('index.php', 'text-white') ?> me-2"></i>
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
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Element selectors
          const sidebarToggle = document.getElementById('sidebar-toggle');
          const sidebar = document.getElementById('sidebar');
          const profileButton = document.getElementById('profile-button');
          const profileDropdown = document.getElementById('profile-dropdown');
          const notificationButton = document.getElementById('notification-button');
          const notificationDropdown = document.getElementById('notification-dropdown');
          const messageButton = document.getElementById('message-button');
          const messageDropdown = document.getElementById('message-dropdown');

          // --- Dark Mode Logic ---
          const themeToggleBtn = document.getElementById('theme-toggle');
          const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
          const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

          // Function to set the correct icon based on the current theme
          const updateThemeIcon = () => {
              if (document.documentElement.classList.contains('dark')) {
                  themeToggleDarkIcon.classList.add('hidden');
                  themeToggleLightIcon.classList.remove('hidden');
              } else {
                  themeToggleDarkIcon.classList.remove('hidden');
                  themeToggleLightIcon.classList.add('hidden');
              }
          };

          // Set initial icon state on page load
          updateThemeIcon();

          themeToggleBtn.addEventListener('click', function() {
              // Toggle the 'dark' class on the <html> element
              document.documentElement.classList.toggle('dark');

              // Update localStorage based on the new state
              if (document.documentElement.classList.contains('dark')) {
                  localStorage.setItem('color-theme', 'dark');
              } else {
                  localStorage.setItem('color-theme', 'light');
              }

              // Update the icon to reflect the new theme
              updateThemeIcon();
          });
          // --- End Dark Mode Logic ---

          // --- Dropdown and Sidebar Logic ---
          function toggleDropdown(dropdown) {
              const allDropdowns = [profileDropdown, notificationDropdown, messageDropdown];
              allDropdowns.forEach(d => {
                  if (d !== dropdown) {
                      d.classList.add('hidden');
                  }
              });
              dropdown.classList.toggle('hidden');
          }

          sidebarToggle.addEventListener('click', (e) => {
              e.stopPropagation();
              sidebar.classList.toggle('-translate-x-full');
          });
          profileButton.addEventListener('click', (e) => {
              e.stopPropagation();
              toggleDropdown(profileDropdown);
          });
          notificationButton.addEventListener('click', (e) => {
              e.stopPropagation();
              toggleDropdown(notificationDropdown);
          });
          messageButton.addEventListener('click', (e) => {
              e.stopPropagation();
              toggleDropdown(messageDropdown);
          });

          document.addEventListener('click', function(e) {
              if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !sidebarToggle.contains(e
                      .target)) {
                  sidebar.classList.add('-translate-x-full');
              }

              const isClickInsideProfile = profileButton.contains(e.target) || profileDropdown.contains(e
                  .target);
              const isClickInsideNotification = notificationButton.contains(e.target) ||
                  notificationDropdown.contains(e.target);
              const isClickInsideMessage = messageButton.contains(e.target) || messageDropdown.contains(e
                  .target);

              if (!isClickInsideProfile) profileDropdown.classList.add('hidden');
              if (!isClickInsideNotification) notificationDropdown.classList.add('hidden');
              if (!isClickInsideMessage) messageDropdown.classList.add('hidden');
          });
      });
  </script>

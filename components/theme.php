<input type="checkbox" id="darkmode-toggle"/>
          <label for="darkmode-toggle" id="darkmode-toggle-label">
            <img src="/src/sun.svg" class="darkmode-toggle-svg" id="darkmode-toggle-sun">
            <img src="/src/moon.svg" class="darkmode-toggle-svg" id="darkmode-toggle-moon">
          </label>

          <script>
            let darkMode = localStorage.getItem('darkmode');

            const enableDarkMode = () => {
              document.documentElement.setAttribute('data-bs-theme', 'dark');
              document.getElementById('darkmode-toggle').setAttribute('checked', '');
              localStorage.setItem('darkmode', 'enabled');
            }

            const disableDarkMode = () => {
              document.documentElement.setAttribute('data-bs-theme', 'light')
              document.getElementById('darkmode-toggle').removeAttribute('checked');
              localStorage.setItem('darkmode', 'disabled');
            }

            if (darkMode === 'enabled') enableDarkMode();

            document.getElementById('darkmode-toggle').addEventListener('click', () => {
              darkMode = localStorage.getItem('darkmode');
              if (darkMode === 'disabled') {
                enableDarkMode();
              } else disableDarkMode();
            });
          </script>
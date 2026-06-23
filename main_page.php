<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Portal</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; color: #333; background: #f1f2f6; }
    .container { width: 90%; max-width: 1200px; margin: auto; }

    /* ---- HERO / SEARCH ---- */
    .hero {
      background: url('banner.jpg') no-repeat center center/cover;
      color: #ffffff; text-align: center; padding: 40px 20px;
    }

    .search-container {
      max-width: 1000px; margin: 40px auto; padding: 28px;
      background: rgba(255, 255, 255, 0.9); border-radius: 12px;
      box-shadow: 0 6px 20px rgba(44,62,80,0.08);
    }

    .search-title { text-align: center; font-size: 28px; margin-bottom: 18px; color: #2C3E50; }

    .search-form { display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center; }
    .search-form input, .search-form button {
      padding: 12px; font-size: 16px; border-radius: 8px; border: 1px solid #d0d6db; width: 100%;
    }

    .form-group { flex: 1; min-width: 200px; position: relative; }
    .form-group button {
      background-color: #34495E; color: white; border: none; cursor: pointer; padding: 12px 18px; border-radius: 8px;
    }
    .form-group button:hover { background-color: #2c3e50; }

    @media (max-width: 768px) { .search-form { flex-direction: column; } }

    .autocomplete-wrapper { position: relative; width: 100%; }

    #suggestions, #suggestions-location {
      position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: white;
      border: 1px solid #e0e4e7; max-height: 200px; overflow-y: auto; z-index: 1000;
      border-radius: 8px; box-shadow: 0 8px 20px rgba(44,62,80,0.06); display: none;
    }

    .suggestion-item { padding: 10px 12px; cursor: pointer; color: #2C3E50; }
    .suggestion-item:hover, .suggestion-item.active { background-color: #f6f9f8; color: #111; }

    /* Remaining layout pieces */
    .moving-container { display: flex; gap: 30px; white-space: nowrap; overflow: hidden; margin-top: 20px; }
    @keyframes scrollLeft { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
    .moving-item { flex: 0 0 auto; background-color: rgb(108, 134, 161); color: white; padding: 15px 30px; border-radius: 5px; animation: scrollLeft 20s linear infinite; text-decoration: none; cursor: pointer; }
    .moving-item:hover { color: inherit; }
    
.dynamic-section {
  padding: 40px 20px;
  background-color: white;
  text-align: center; /* center the section */
}
  </style>
</head>
<body>
<?php include 'header.php' ?>
  <div class="hero">
    <div class="search-container">
      <div class="search-title">Find Your Dream Job</div>
      <form class="search-form" action="login/login.php" method="GET" autocomplete="off">
        <div class="form-group autocomplete-wrapper" id="jobWrapper">
          <input type="text" id="jobInput" name="title" placeholder="Job Title or Keyword" />
          <div id="suggestions" role="listbox" aria-label="Job suggestions"></div>
        </div>

        <div class="form-group autocomplete-wrapper" id="locWrapper">
          <input type="text" id="locationInput" name="location" placeholder="Location (e.g., Rajkot)" />
          <div id="suggestions-location" role="listbox" aria-label="Location suggestions"></div>
        </div>

        <div class="form-group">
          <button type="submit">Search</button>
        </div>
      </form>
    </div>
  </div>

  <section class="dynamic-section">
    <h2>🔥 Latest Job Openings</h2>
    <div class="moving-container">
      <a href="jobs/job_list.php" class="moving-item">💼 Software Engineer at Google</a>
      <a href="jobs/job_list.php" class="moving-item">💼 UI/UX Designer at Adobe</a>
      <a href="jobs/job_list.php" class="moving-item">💼 Data Analyst at Microsoft</a>
      <a href="jobs/job_list.php" class="moving-item">💼 Backend Developer at Amazon</a>
      <a href="jobs/job_list.php" class="moving-item">💼 Digital Marketer at Netflix</a>
    </div>
  </section>

  <script>
    // ---------- Autocomplete logic ----------
    const jobSuggestions = [
      "Graphic designer", "project manager", "Customer Support Associate", "Backend Developer", "Software Engineer",
      "Frontend Developer", "Data scientist", "digital marketing", "Graphic Designer",
      "Digital Marketing Specialist", "HR Executive", "Mechanical Engineer",
      "Data Analyst", "Product Manager","UI/UX Designer","devOps Engineer","Web Application Engineer",
      "Content Writer"
    ];

    const locationSuggestions = [
      "Rajkot", "Ahmedabad", "Gurgaon", "New york", "Mumbai",
      "Pune", "Delhi", "Bangalore", "Chennai"
    ];

    function setupAutocomplete({ input, box, suggestions, wrapper }) {
      let currentFocus = -1;

      function renderList(query = "") {
        box.innerHTML = "";
        const q = query.trim().toLowerCase();
        const filtered = q === "" ? suggestions.slice(0, 10) : suggestions.filter(item => item.toLowerCase().includes(q));
        if (!filtered.length) { box.style.display = "none"; return; }
        filtered.forEach(itemText => {
          const div = document.createElement("div");
          div.className = "suggestion-item";
          div.textContent = itemText;
          div.tabIndex = -1;
          div.addEventListener("click", () => { input.value = itemText; closeBox(); input.focus(); });
          box.appendChild(div);
        });
        box.style.display = "block";
      }

      function closeBox() { box.innerHTML = ""; box.style.display = "none"; currentFocus = -1; }

      input.addEventListener("focus", () => renderList(input.value));
      input.addEventListener("click", (e) => { e.stopPropagation(); renderList(input.value); });
      input.addEventListener("input", () => renderList(input.value));

      input.addEventListener("keydown", (e) => {
        const items = box.querySelectorAll(".suggestion-item");
        if (!items.length) return;
        if (e.key === "ArrowDown") { e.preventDefault(); currentFocus = (currentFocus + 1) % items.length; updateActive(items); }
        else if (e.key === "ArrowUp") { e.preventDefault(); currentFocus = (currentFocus - 1 + items.length) % items.length; updateActive(items); }
        else if (e.key === "Enter") { e.preventDefault(); if (currentFocus > -1 && items[currentFocus]) items[currentFocus].click(); }
        else if (e.key === "Escape") { closeBox(); }
      });

      function updateActive(items) {
        items.forEach(it => it.classList.remove("active"));
        if (currentFocus >= 0 && items[currentFocus]) {
          items[currentFocus].classList.add("active");
          const activeEl = items[currentFocus];
          const containerTop = box.scrollTop;
          const containerBottom = containerTop + box.clientHeight;
          const elTop = activeEl.offsetTop;
          const elBottom = elTop + activeEl.offsetHeight;
          if (elTop < containerTop) box.scrollTop = elTop;
          else if (elBottom > containerBottom) box.scrollTop = elBottom - box.clientHeight;
        }
      }

      document.addEventListener("click", (e) => { if (!wrapper.contains(e.target)) closeBox(); });

      return { close: closeBox };
    }

    setupAutocomplete({
      input: document.getElementById("jobInput"),
      box: document.getElementById("suggestions"),
      suggestions: jobSuggestions,
      wrapper: document.getElementById("jobWrapper")
    });

    setupAutocomplete({
      input: document.getElementById("locationInput"),
      box: document.getElementById("suggestions-location"),
      suggestions: locationSuggestions,
      wrapper: document.getElementById("locWrapper")
    });
  </script>

<?php include 'footer.php'; ?>
</body>
</html>

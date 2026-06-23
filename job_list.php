<?php
session_start();
include '../login/db_con.php';
$conn = new mysqli("localhost", "root", "", "jobwave");

// Build filter conditions
$where = [];

// Direct job click highlight
if (!empty($_GET['job_id'])) {
    $jobId = (int)$_GET['job_id'];
    $where[] = "id = $jobId";
}

// Experience filter
if (!empty($_GET['experience'])) {
    $exp = (int)$_GET['experience'];
    if ($exp === 0) {
        // Fresher
        $where[] = "(experience_range LIKE '0%' OR experience_range LIKE '%Fresher%')";
    } else {
        $where[] = "CAST(SUBSTRING_INDEX(experience_range, '-', -1) AS UNSIGNED) <= $exp";
    }
}

// Location filter
if (!empty($_GET['location'])) {
    $locations = $_GET['location'];
    $escaped_locations = array_map(function($loc) use ($conn) {
        return "'" . $conn->real_escape_string($loc) . "'";
    }, $locations);
    $locationList = implode(",", $escaped_locations);
    $where[] = "location IN ($locationList)";
}

// Early Applicant filter
if (!empty($_GET['early_applicant'])) {
    $where[] = "is_early_applicant = 1";
}

// Build SQL
$whereSQL = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
$sql = "SELECT * FROM jobs $whereSQL ORDER BY posted_on DESC";
$result = $conn->query($sql);

// Time ago function
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return $diff . " seconds";
    elseif ($diff < 3600) return floor($diff / 60) . " minutes";
    elseif ($diff < 86400) return floor($diff / 3600) . " hours";
    else return floor($diff / 86400) . " days";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Listings</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f6fa; margin: 0; padding: 0; }
        header { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: #fff; border-bottom: 1px solid #ddd; height: 70px; display: flex; align-items: center; padding: 0 20px; }
        .content { display: flex; gap: 30px; padding: 20px; padding-top: 90px; }
        .sidebar { position: sticky; top: 90px; left: 20px; width: 250px; max-height: calc(100vh - 110px); overflow-y: auto; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 20px; }
        .job-section { flex: 1; }
        .sidebar h3 { font-size: 18px; margin-bottom: 20px; }
        .job-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 20px; display: flex; gap: 20px; transition: all 0.2s ease-in-out; }
        .job-card:hover { transform: translateY(-3px); }
        .job-info { flex-grow: 1; }
        .job-title { font-weight: bold; font-size: 18px; }
        .company { color: #666; font-size: 15px; margin-top: 4px; }
        .details { margin-top: 12px; display: flex; gap: 20px; font-size: 14px; color: #444; }
        .badge { background-color: #e6f4ff; color: #0073e6; display: inline-block; padding: 4px 10px; font-size: 12px; border-radius: 5px; margin-top: 10px; }
        .posted { color: #999; font-size: 13px; margin-top: 12px; }
        .actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
        .apply-btn { padding: 8px 14px; border-radius: 10px; font-size: 14px; cursor: pointer; border: none; background-color: #f0e9ff; color: #7a2ff2; text-decoration: none; }
        .job-logo img { max-width: 80px; max-height: 80px; object-fit: contain; border-radius: 10px; }
        input[type="range"] { width: 100%; margin-top: 8px; }
        output { margin-left: 10px; font-weight: bold; }
        select[multiple] { width: 100%; height: 120px; border-radius: 6px; padding: 8px; }
        .clear-btn { display: inline-block; margin-left: 10px; padding: 6px 12px; background: #ccc; color: #000; text-decoration: none; border-radius: 6px; font-size: 13px; }
    </style>
</head>
<body>
<?php include '../header.php'; ?>

<div class="content">
    <div class="sidebar">
        <h3>Filters</h3>
        <hr>
        <form method="GET" action="">
            <p><strong>Experience</strong></p>
            <input type="range" name="experience" min="0" max="50" 
                value="<?= isset($_GET['experience']) ? (int)$_GET['experience'] : 0 ?>" 
                oninput="experienceOutput.value = (this.value == 0 ? 'Fresher' : this.value + ' Year+')">
            <output id="experienceOutput"><?= isset($_GET['experience']) ? (($_GET['experience'] == 0) ? 'Fresher' : (int)$_GET['experience'].' Year+') : 'Fresher' ?></output>
            <hr>
            <p><strong>Location</strong></p>
            <select name="location[]" multiple>
                <?php
                $locations = ['Ahmedabad','Rajkot','Mumbai','Pune','Chennai','Bangalore','Delhi','New York','Gurgaon'];
                $selectedLocations = $_GET['location'] ?? [];
                foreach ($locations as $loc) {
                    $sel = in_array($loc, $selectedLocations) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($loc)."' $sel>".htmlspecialchars($loc)."</option>";
                }
                ?>
            </select>
            <hr>
            <label><input type="checkbox" name="early_applicant" value="1" <?= isset($_GET['early_applicant']) ? 'checked' : '' ?>> Early Applicant Only</label>
            <br><br>
            <input type="submit" value="Apply Filters">
            <a href="job_list.php" class="clear-btn">Clear Filters</a>
        </form>
    </div>

    <div class="job-section">
        <h2>Available Jobs</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $highlight = (isset($_GET['job_id']) && $_GET['job_id'] == $row['id']) ? 'style="border:2px solid #7a2ff2; box-shadow:0 0 10px rgba(122,47,242,0.4);"' : '';
            ?>
            <div class="job-card" <?= $highlight ?>>
                <?php if (!empty($row['logo'])): ?>
                    <div class="job-logo"><img src="../admin/uploads/<?= htmlspecialchars($row['logo']) ?>" alt="Company Logo"></div>
                <?php endif; ?>
                <div class="job-info">
                    <div class="job-title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="company"><?= htmlspecialchars($row['company']) ?></div>
                    <div class="details">
                        <span>🧳 <?= htmlspecialchars($row['experience_range']) ?></span>
                        <span>💰 <?= htmlspecialchars($row['salary_range']) ?></span>
                        <span>📍 <?= htmlspecialchars($row['location']) ?></span>
                    </div>
                    <?php if (!empty($row['is_early_applicant'])): ?>
                        <div class="badge">Early Applicant</div>
                    <?php endif; ?>
                    <div class="posted">Posted <?= timeAgo($row['posted_on']) ?> ago</div>
                </div>
                <div class="actions">
                    <a href="../login/login.php" class="apply-btn">🚀 Apply Now</a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs found matching your filters.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../footer.php';?>
</body>
</html>

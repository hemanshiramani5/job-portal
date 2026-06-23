<?php
// admin_users.php
session_start();
include "../login/db_con.php"; // Adjust path if needed

// --------------------
// HANDLE DELETE USER
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    if ($deleteId > 0) {
        // Delete user; applications will auto-delete due to ON DELETE CASCADE
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->close();

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET));
        exit;
    }
}

// --------------------
// SEARCH & PAGINATION
// --------------------
$q       = isset($_GET['q']) ? trim($_GET['q']) : '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$sort    = 'id';
$dir     = 'ASC';

$whereSql = '';
$params   = [];
$types    = '';

if ($q !== '') {
    $whereSql = " WHERE username LIKE ? ";
    $params[] = '%' . $q . '%';
    $types   .= 's';
}

// Count total users
$countSql = "SELECT COUNT(*) AS cnt FROM users" . $whereSql;
$countStmt = $conn->prepare($countSql);
if ($types) {
    function refValues($arr) {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
    $countStmt->bind_param($types, ...refValues($params));
}
$countStmt->execute();
$countRes = $countStmt->get_result()->fetch_assoc();
$total    = (int)$countRes['cnt'];
$countStmt->close();

$lastPage = max(1, (int)ceil($total / $perPage));
$page     = min($page, $lastPage);
$offset   = ($page - 1) * $perPage;

// Fetch users
$listSql = "SELECT id, username FROM users $whereSql ORDER BY $sort $dir LIMIT ? OFFSET ?";
$listStmt = $conn->prepare($listSql);

if ($types) {
    $bindTypes = $types . 'ii';
    $params2   = array_merge($params, [$perPage, $offset]);
    $listStmt->bind_param($bindTypes, ...refValues($params2));
} else {
    $listStmt->bind_param('ii', $perPage, $offset);
}

$listStmt->execute();
$result = $listStmt->get_result();

// --------------------
// HELPER FUNCTIONS
// --------------------
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function qs(array $overrides = []) {
    $base = [
        'q'    => $_GET['q'] ?? '',
        'page' => $_GET['page'] ?? 1,
    ];
    $merged = array_merge($base, $overrides);
    return '?' . http_build_query($merged);
}
?>

<?php include 'main_page.php'; ?> <!-- your header, nav, etc. -->

<style>
.container-card { max-width:980px; margin:40px auto; background:#fff; padding:20px; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.06); border:1px solid #f0f0f0; }
.toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; }
.search-wrap { display:flex; align-items:center; gap:10px; flex:1 1 360px; }
.search-input { width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:12px; outline:none; transition:border-color .2s, box-shadow .2s; }
.search-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.15); }
.pill, .pill:link, .pill:visited { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#fafafa; text-decoration:none; font-size:14px; color: inherit; }
.pill:hover { background:#f3f4f6; }
.table-wrap { margin-top:16px; overflow:auto; border-radius:12px; border:1px solid #eee; }
table { width:100%; border-collapse:collapse; }
thead th { position:sticky; top:0; background:#f9fafb; text-align:left; font-weight:600; padding:12px; border-bottom:1px solid #eee; white-space:nowrap; }
tbody td { padding:12px; border-bottom:1px solid #f3f4f6; }
tbody tr:hover { background:#fcfcff; }
.id-badge { display:inline-block; padding:4px 10px; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:600; font-size:12px; border:1px solid #e0e7ff; }
.username { display:flex; align-items:center; gap:10px; font-weight:500; }
.avatar { width:28px; height:28px; border-radius:50%; background:#e5e7eb; display:inline-flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#374151; }
.muted { color:#6b7280; }
.kpi { display:flex; gap:18px; align-items:center; margin:10px 0 4px; flex-wrap:wrap; }
.kpi .chip { background:#f8fafc; border:1px solid #e5e7eb; border-radius:12px; padding:6px 10px; font-size:12px; }
table tbody td:last-child { white-space: nowrap; width: 100px; }
table tbody button { background:#ef4444; border:none; color:#fff; padding:6px 12px; border-radius:8px; cursor:pointer; font-size:14px; transition: background-color 0.2s ease; }
table tbody button:hover { background:#dc2626; }
.pagination { margin-top:16px; display:flex; gap:8px; }
.pagination a { padding:6px 12px; border-radius:8px; border:1px solid #e5e7eb; text-decoration:none; color:#111; }
.pagination a:hover { background:#f3f4f6; }
</style>

<div class="container-card">
  <h2 style="margin-top:0; margin-bottom:8px;">Registered Users</h2>

  <div class="kpi">
    <span class="chip">Total: <strong><?php echo $total; ?></strong></span>
    <span class="chip">Page: <strong><?php echo $page; ?></strong> / <?php echo $lastPage; ?></span>
  </div>

  <div class="toolbar">
    <form class="search-wrap" method="get" action="">
      <input class="search-input" type="text" name="q" placeholder="Search username…" value="<?php echo h($q); ?>" />
      <button class="pill" type="submit" title="Search">Search</button>
      <?php if ($q !== ''): ?>
        <a class="pill" href="<?php echo qs(['q'=>'','page'=>1]); ?>" title="Clear">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): 
              $id = (int)$row['id'];
              $usr = $row['username'];
              $initials = strtoupper(mb_substr($usr,0,1,'UTF-8'));
          ?>
            <tr>
              <td><span class="id-badge">#<?php echo $id; ?></span></td>
              <td>
                <div class="username">
                  <span class="avatar"><?php echo h($initials); ?></span>
                  <span><?php echo h($usr); ?></span>
                </div>
              </td>
              <td>
                <form method="post" action="" onsubmit="return confirm('Delete user <?php echo h($usr); ?>?');" style="margin:0;">
                  <input type="hidden" name="delete_id" value="<?php echo $id; ?>">
                  <button type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="3" class="muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php if($page>1): ?>
      <a href="<?php echo qs(['page'=>$page-1]); ?>">&laquo; Prev</a>
    <?php endif; ?>
    <?php if($page<$lastPage): ?>
      <a href="<?php echo qs(['page'=>$page+1]); ?>">Next &raquo;</a>
    <?php endif; ?>
  </div>
</div>

<?php
$listStmt->close();
$conn->close();
?>

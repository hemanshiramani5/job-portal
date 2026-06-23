<?php include 'main_page.php'; ?> <!-- Session/auth/header -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - Manage Job Seeker Reviews</title>
<style>
  .review-page {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
    background: #f4f6f8;
    color: #333;
  }
  .review-page h1 {
    text-align: center;
    color: #34495e;
    margin-bottom: 30px;
  }
  .review-page table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 5px rgba(0,0,0,0.1);
  }
  .review-page th, .review-page td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  .review-page th { background-color: #2c3e50; color: white; }
  .review-page tr:hover { background-color: #f1f1f1; }
  .delete-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  .delete-btn:hover { background-color: #c0392b; }
  .stars { color: #f39c12; font-size: 18px; }
  .no-reviews { text-align: center; margin-top: 40px; color: #777; font-style: italic; }
</style>
</head>
<body>

<div class="review-page">
  <h1>Admin - Manage Job Seeker Reviews</h1>

  <table id="reviewsTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Rating</th>
        <th>Review</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <p id="noReviewsMsg" class="no-reviews" style="display:none;">No reviews to display.</p>
</div>

<script>
const reviewsTableBody = document.querySelector('#reviewsTable tbody');
const noReviewsMsg = document.getElementById('noReviewsMsg');

function escapeHtml(text) {
  return text.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

function loadReviews() {
  const reviews = JSON.parse(localStorage.getItem('jobSeekerReviews')) || [];
  reviewsTableBody.innerHTML = '';
  noReviewsMsg.style.display = reviews.length ? 'none' : 'block';

  reviews.forEach((review, index) => {
    const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${escapeHtml(review.name)}</td>
      <td class="stars">${stars}</td>
      <td>${escapeHtml(review.text)}</td>
      <td>${new Date(review.date).toLocaleString()}</td>
      <td><button class="delete-btn" data-index="${index}">Delete</button></td>
    `;
    reviewsTableBody.appendChild(row);
  });

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', e => deleteReview(parseInt(e.target.dataset.index)));
  });
}

function deleteReview(index) {
  if (!confirm('Are you sure you want to delete this review?')) return;
  const reviews = JSON.parse(localStorage.getItem('jobSeekerReviews')) || [];
  reviews.splice(index, 1);
  localStorage.setItem('jobSeekerReviews', JSON.stringify(reviews));
  loadReviews();
}

loadReviews();
</script>

</body>
</html>

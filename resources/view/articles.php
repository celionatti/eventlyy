<?php

/**
 * Framework Title: PhpStrike Framework
 * Creator: Celio natti
 * version: 1.0.0
 * Year: 2023
 * 
 * 
 * This view page start name{style,script,content} 
 * can be edited, base on what they are called in the layout view
 */

use PhpStrike\app\components\NavComponent;
use PhpStrike\app\components\FooterComponent;

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>


<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<!-- Blog Header Section -->
<section class="blog-header">
    <div class="container text-center">
      <h1 class="display-5 fw-bold text-white mb-4">Eventlyy Blog</h1>
      <p class="lead text-white mb-5">Insights, tips, and stories from the world of events</p>

      <!-- Search Bar -->
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 px-4">
          <form action="<?= URL_ROOT . "/articles/search" ?>" method="GET" class="search-container">
            <div class="input-group">
              <input type="text" class="form-control search-input" name="query" placeholder="Search for articles...">
              <button class="btn search-btn" type="submit">
                <i class="fas fa-search me-1"></i> <span class="btn-text">Search</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>

<!-- Main Content -->
<div class="container mt-4">

  <?php if($featured): ?>
    <!-- Featured Article -->
    <div class="featured-article">
      <img src="<?= get_image($featured['image'], "default") ?>" class="featured-img" alt="<?= $featured['title'] ?>">
      <div class="featured-content">
        <span class="featured-badge"><?= $featured['tag'] ?></span>
        <h2 class="featured-title"><?= $featured['title'] ?></h2>
        <p class="mb-4"><?= StringUtils::create(htmlspecialchars_decode(nl2br($featured['content'])))->excerpt(150) ?></p>
        <a href="<?= URL_ROOT . "/articles/view/{$featured['article_id']}" ?>" class="mb-3 btn btn-outline-secondary">Read More <i class="fas fa-arrow-right ms-1"></i></a>
        <div class="featured-meta">
          <img src="<?= get_image("", "avatar") ?>" class="featured-author-img" alt="Author Avatar">
          <span>By <?= $featured['contributors'] ?> • <?= TimeDateUtils::create($featured['created_at'])->toCustomFormat("M j, Y") ?> • <?= calReadTime($featured['content'], "min read") ?></span>
        </div>
      </div>
    </div>
  <?php endif; ?>

    <div class="row">
      <!-- Blog Content -->
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4 sort-container">
          <div>
            <h4 style="color: var(--dark-blue);">Recent Articles</h4>
          </div>
          <!-- <div class="d-flex align-items-center">
            <label for="sort-by" class="me-2">Sort by:</label>
            <select id="sort-by" class="sort-select">
              <option value="newest">Newest First</option>
              <option value="oldest">Oldest First</option>
              <option value="popular">Most Popular</option>
            </select>
          </div> -->
        </div>

        <div class="row">
        <?php if($articles): ?>
        <?php foreach($articles as $article): ?>
          <!-- Blog Card -->
          <div class="col-md-6 mb-4">
            <div class="card blog-card h-100">
              <img src="<?= get_image($article['image'], "default") ?>" class="card-img-top blog-img" alt="Articles" loading="lazy">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="blog-date"><?= TimeDateUtils::create($article['created_at'])->toCustomFormat("M j, Y") ?></span>
                  <span class="blog-category"><?= $article['tag'] ?></span>
                </div>
                <h5 class="blog-title"><?= $article['title'] ?></h5>
                <div class="blog-excerpt"><?= StringUtils::create(htmlspecialchars_decode(nl2br($article['content'])))->excerpt(200) ?></div>
                <a href="<?= URL_ROOT . "/articles/view/{$article['article_id']}" ?>" class="read-more">Read More <i class="fas fa-arrow-right ms-1"></i></a>
                <div class="blog-author">
                  <img src="<?= get_image("", "avatar") ?>" class="author-img" alt="Author Avatar">
                  <div>
                    <p class="author-name"><?= $article['contributors'] ?></p>
                    <small class="text-muted"><?= calReadTime($article['content'], " min read") ?></small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          <nav aria-label="Page navigation">
            <?= $pagination ?>
          </nav>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- About Blog -->
        <div class="blog-sidebar mb-4">
          <h4 class="sidebar-heading">About Our Blog</h4>
          <div><?= setting("about", "Founded in 2025, we've revolutionized ticket purchasing with our user-friendly interface and commitment to fair pricing.") ?></div>
          <div class="mt-3">
            <a href="#" class="btn btn-outline-primary" style="border-radius: 20px; border-color: var(--medium-blue); color: var(--dark-blue);">
              <i class="fas fa-info-circle me-1"></i> Learn More
            </a>
          </div>
        </div>

        <?php if($populars): ?>
        <!-- Popular Posts -->
        <div class="blog-sidebar mb-4">
          <h4 class="sidebar-heading">Popular Posts</h4>
          <?php foreach($populars as $popular): ?>
            <a href="<?= URL_ROOT . "/articles/view/{$popular['article_id']}" ?>" class="text-black text-decoration-none">
            <div class="sidebar-post">
              <img src="<?= get_image($popular['image'], "default") ?>" class="sidebar-post-img" alt="Popular Post">
              <div>
                <h5 class="sidebar-post-title"><?= $popular['title'] ?></h5>
                <p class="sidebar-post-date"><?= TimeDateUtils::create($article['created_at'])->toCustomFormat("M j, Y") ?></p>
              </div>
            </div>
            </a>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>

        <!-- Newsletter Subscription -->
        <!-- <div class="blog-sidebar mb-4">
          <h4 class="sidebar-heading">Subscribe to Our Newsletter</h4>
          <p>Get the latest event trends and tips directly to your inbox</p>
          <form>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn subscribe-btn">Subscribe Now</button>
          </form>
        </div> -->
      </div>
    </div>
</div>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>
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
      <h1 class="display-5 fw-bold text-white mb-4">Searching Articles For...</h1>
      <p class="lead text-white mb-5 text-uppercase"><?= $search ?></p>

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

    <div class="row">
      <!-- Blog Content -->
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4 sort-container">
          <div>
            <h4 style="color: var(--dark-blue);"><?= $count ?> Articles Found</h4>
          </div>

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

      </div>
    </div>
</div>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>
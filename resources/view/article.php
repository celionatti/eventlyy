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

<!-- Article Header Section -->
<header class="article-header">
    <div class="article-header-overlay"></div>
    <div class="container article-header-content">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <span class="article-category"><?= $article['tag'] ?></span>
          <h1 class="article-title"><?= $article['title'] ?></h1>
          <div class="article-meta">
            <img src="<?= get_image("", "avatar") ?>" class="article-author-img" alt="Michael Chen">
            <div class="article-author-info">
              <p class="article-author-name">By <?= $article['contributors'] ?></p>
              <p class="article-date"><?= TimeDateUtils::create($article['created_at'])->toCustomFormat("F j, Y") ?> • <?= calReadTime($article['content'], " min read") ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="row justify-content-center">
      <!-- Article Content -->
      <div class="col-lg-8">
        <div class="article-content">

          <div class="article-image">
            <img src="<?= get_image($article['image']) ?>" alt="<?= $article['title'] ?>">
            <p class="caption"><?= $article['title'] ?></p>
          </div>

        <?= StringUtils::create(htmlspecialchars_decode(nl2br($article['content']))) ?>

        <!-- Social Share -->
          <div class="social-share">
            <p>Share this article:</p>
            <div class="share-links">
              <a href="https://www.facebook.com/sharer/<?= StringUtils::create($article['meta_title'])->toSlug() ?>" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="https://twitter.com/intent/tweet?url=<?= URL_ROOT . "/articles/view/{$article['article_id']}" ?>&text=<?= $article['title'] ?>" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
              <a href="https://api.whatsapp.com/send?text=<?= $article['title'] ?>: <?= URL_ROOT . "/articles/view/{$article['article_id']}" ?>" class="share-btn linkedin"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>

        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="blog-sidebar">
          <div class="mb-4">
            <h5 class="sidebar-heading">Search</h5>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search articles...">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <?php if($populars): ?>
          <div class="mb-4">
            <h5 class="sidebar-heading">Popular Posts</h5>
            <?php foreach($populars as $popular): ?>
            <a href="<?= URL_ROOT . "/articles/view/{$popular['article_id']}" ?>" class="text-black text-decoration-none">
            <div class="sidebar-post">
              <img src="<?= get_image($popular['image'], "default") ?>" class="sidebar-post-img" alt="Popular post">
              <div>
                <p class="sidebar-post-title"><?= $popular['title'] ?></p>
                <p class="sidebar-post-date"><?= TimeDateUtils::create($article['created_at'])->toCustomFormat("M j, Y") ?></p>
              </div>
            </div>
            </a>
            <?php endforeach; ?>
            <!-- More popular posts -->
          </div>
        <?php endif; ?>

        </div>
      </div>
    </div>
</main>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>
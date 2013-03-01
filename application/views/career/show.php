<section id="career_content" class="group">
    <img id="main_image" class="left" src="<?php echo BASE_PATH."/public/images/".($career['link']) ?>">
    <article class="left">
        <h1 class="purple_text center_text"><?php echo $career['name']?> career</h1>
        <p class="background_dark_grey medium_round_corner"><?php echo $career['description']?></p>
    </article>
    <aside class="left background_dark_grey center_text medium_round_corner">
        <?php $contents = json_decode($career['content'], true); ?>
        <?php foreach($contents as $content): ?>
            <?php foreach($content as $ck => $cv): ?>
                <h3 class="purple_text"><?php echo($ck)?></h3>
                <p><?php echo($cv) ?></p>
            <?php endforeach ?>
        <?php endforeach ?>
    </aside>
</section>
<section>
    <span></span>
    <article>
        <?php foreach($gallery as $image): ?>
        <img src="<?php echo BASE_PATH."/public/images/".$image['link'] ?>" title="<?php echo $image['description'] ?>">
        <?php endforeach ?>
    </article>
    <span></span>
</section>
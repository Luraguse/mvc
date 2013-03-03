<aside id="latest_news" class="left main_aside background_dark_grey medium_round_corner">
    <h4 class="purple_text center_text">Latest News</h4>

    <?php foreach($news as $newsValue): ?>
        <p>
            <em>
                <?php echo $newsValue["created_at"]; ?>
                : 
            </em>
            
            <?php 
            $decoded = json_decode($newsValue["content"],true);
            
                    switch ($decoded['type']) {
                        case "video":
                            echo "Added a new video";
                ?>
                <iframe width="210" height="118" src="<?php echo $decoded['content']; ?>" frameborder="0" allowfullscreen=""></iframe>
                <?php
                            break;
                        case "news":
                            echo $decoded['content'];
                ?>
                <?php
                            break;
                        case "image":
                            echo "Added a new photo";
                ?>
                <a href="<?php echo BASE_PATH."/career/show/".$decoded['gallery']; ?>">
                    <img src="<?php echo BASE_PATH."/public/images/".$decoded['content'];?>">
                </a>
                <?php       
                            break;
                ?>
                <?php
                        default:
                            break;
                    }
                ?>
        </p>
    <?php endforeach; ?>

</aside>
<section id="home_content" class="left">
    <img src="<?php echo BASE_PATH ?>/public/images/main.png" alt="Trisha Hershberger" title="Trisha Hershberger">
    <h1 class="purple_text cursive">Trisha Hershberger's<br>Official Website</h1>
    <p class="background_dark_grey medium_round_corner">
        Welcome to Trisha Hershberger’s official website.
        Here you can view Trisha’s resume, demo reels, bio and much more!
        Feel free to look around.
    </p>
    <!-- twitter, facebook, imdb links -->
</section>
<aside id="latest_videos" class="left main_aside background_dark_grey medium_round_corner">
    <h4 class="purple_text center_text">Latest Videos</h4>
    <p>
        <iframe width="210" height="118" src="http://www.youtube.com/embed/9rRCaUf02uk" frameborder="0" allowfullscreen></iframe>
    </p>
    <p>
        <iframe width="210" height="118" src="http://www.youtube.com/embed/9rRCaUf02uk" frameborder="0" allowfullscreen></iframe>
    </p>
    <p>
        <iframe width="210" height="118" src="http://www.youtube.com/embed/9rRCaUf02uk" frameborder="0" allowfullscreen></iframe>
    </p>
</aside>
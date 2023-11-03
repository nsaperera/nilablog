<?php include TEMPLATE_PATH . "header.php";?>
    
    <div class="container">
        <main>
            <?php
                foreach($blog_list as $v){ ?>
                    <article class="post">
                        <h2><?php echo $v["title"];?></h2>
                        <p><pre><?php echo $v["content"];?></pre></p>
                        <small>Posted on <?php echo $v["created_time"];?></small>
                    </article>
                <?php }
            ?>
        </main>
    </div>

<?php include TEMPLATE_PATH . "footer.php";?>
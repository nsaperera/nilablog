<?php include TEMPLATE_PATH . "header.php";?>
    
    <div class="container">
        <!-- On rows -->
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Create Date</th>
                </tr>
                <?php
                foreach($blog_list as $v){ ?>
                    <tr>
                        <td><?php echo $v["id"]?></td>
                        <td><?php echo $v["title"]?></td>
                        <td><?php echo $v["content"]?></td>
                        <td><?php echo $v["created_time"]?></td>
                    </tr>
                <?php }
            ?>
            </table>
        </div>

    </div>

<?php include TEMPLATE_PATH . "footer.php";?>
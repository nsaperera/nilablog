<?php include TEMPLATE_PATH . "header.php";?>

        <div class="blog-post">
            <div class="container">
                
                <form action="<?php echo BASE_URL;?>blog/save_post" method="post">
                    <div class="modal-body">
                        <h4>Post Blog</h4>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" name="content" id="content" rows="6"></textarea>                                
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary saveblog">SAVE BLOG</button>
                    </div>
                </form>
            </div>
        </div>

<?php include TEMPLATE_PATH . "footer.php";?>

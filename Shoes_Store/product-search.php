<?php include('partials_front/menu.php'); ?>


<section class="product-search text-center">
    <div class="container">
        <?php
        // Ensure database connection is set
        if (!isset($conn)) {
            die("Database connection not established.");
        }

        // Get the Search Keyword
        if (isset($_POST['search'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search']);
            echo "<h2>Product on Your Search <a href='#' class='text-white'>{$search}</a></h2>";
        } else {
            header('location:' . SITEURL);
            exit(); // Stop execution
        }
        ?>

    </div>
</section>

<section class="product-menu">
    <div class="container">
        <h2 class="text-center">Search Results</h2>

        <?php
        // Enhanced SQL query to search by product name, description, AND category name
        $search = $_POST['search'];

        $sql = "SELECT tbl_shoes.*, tbl_category.title as category_title 
                FROM tbl_shoes 
                LEFT JOIN tbl_category ON tbl_shoes.category_id = tbl_category.id 
                WHERE tbl_shoes.title LIKE '%$search%' 
                OR tbl_shoes.description LIKE '%$search%' 
                OR tbl_category.title LIKE '%$search%'";

        $res = mysqli_query($conn, $sql);

        $count = mysqli_num_rows($res);

        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                // $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $description = $row['description'];
                $image_name = $row['image_name'];
        ?>
                <div class="product-menu-box">
                    <div class="product-menu-img">
                        <?php
                        if ($image_name == "") {
                            echo "<div class='error'>Image not available</div>";
                        } else {
                        ?>
                            <img src="<?php echo SITEURL; ?>images/shoes/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                        <?php
                        }
                        ?>
                    </div>

                    <div class="product-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="product-price">Rp<?php echo $price; ?></p>
                        <p class="product-detail">
                            <?php echo $description; ?>
                        </p>
                        <br>
                        <a href="<?php echo SITEURL; ?>order.php?shoes_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='error'>No product found.</div>";
        }
        ?>
        <div class="clearfix"></div>
    </div>
</section>

<?php include('partials_front/footer.php'); ?>
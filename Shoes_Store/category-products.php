<?php include('partials_front/menu.php'); ?>


<?php
// Check if category_id is passed
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Get the category title based on category_id
    $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $category_title = $row['title'];
} else {
    // Redirect to home page if category_id is not set
    header('location:' . SITEURL);
}
?>

<!-- PRODUCT SEARCH Section Starts Here -->
<section class="product-search text-center">
    <div class="container">
        <h2>Products on <a href="#" class="text-white"><?php echo $category_title; ?></a></h2>
    </div>
</section>
<!-- PRODUCT SEARCH Section Ends Here -->

<!-- PRODUCT MENU Section Starts Here -->
<section class="product-menu">
    <div class="container">
        <h2 class="text-center">Products list</h2>

        <?php
        // Get products from tbl_shoes by category_id
        $sql2 = "SELECT * FROM tbl_shoes WHERE category_id = $category_id";
        $res2 = mysqli_query($conn, $sql2);
        $count2 = mysqli_num_rows($res2);

        if ($count2 > 0) {
            // Products available
            while ($row2 = mysqli_fetch_assoc($res2)) {
                // $id = $row2['id'];
                $title = $row2['title'];
                $price = $row2['price'];
                $description = $row2['description'];
                $image_name = $row2['image_name'];
        ?>
                <div class="product-menu-box">
                    <div class="product-menu-img">
                        <?php
                        if ($image_name == "") {
                            echo "<div class='error'>Image not Available.</div>";
                        } else {
                        ?>
                            <img src="<?php echo SITEURL; ?>images/shoes/<?php echo $image_name; ?>"
                                alt="<?php echo $title; ?>"
                                class="img-responsive img-curve">
                        <?php
                        }
                        ?>
                    </div>

                    <div class="product-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="product-price">Rp<?php echo $price; ?></p>
                        <p class="product-detail"><?php echo $description; ?></p>
                        <br>
                        <a href="<?php echo SITEURL; ?>order.php?shoes_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div>
        <?php
            }
        } else {
            // No products found
            echo "<div class='error'>No products available in this category.</div>";
        }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- PRODUCT MENU Section Ends Here -->

<?php include('partials_front/footer.php'); ?>
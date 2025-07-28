<?php 
include('partials_front/menu.php'); 

// Check if user is logged in
$user_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true;
$user_id = $user_logged_in ? $_SESSION['id'] : null;

// Get user info if logged in
$user_info = null;
if ($user_logged_in && $user_id) {
    $user_query = "SELECT username FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_info = mysqli_fetch_assoc($user_result);
    }
}
?>

<!-- PRODUCT sEARCH Section Starts Here -->
<section class="product-search text-center">
    <div class="container">

        <form action="<?php echo SITEURL;?>product-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for Product.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>

    </div>
</section>
<!-- PRODUCT sEARCH Section Ends Here -->
    <?php
        if(isset($_SESSION['order']))
        {
            echo $_SESSION['order'];
            unset($_SESSION['order']);
        }
    ?>

<!-- CAtegories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Products</h2>
        <?php
        $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 6";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $title = $row['title'];
                $image_name = $row['image_name'];
        ?>
                <a href="<?php echo SITEURL;?>category-products.php?category_id=<?php echo $id;?>">
                    <div class="box-3 float-container">
                        <?php
                        if ($image_name == "") {
                            echo "<div class='error'>Image Not Available</div>";
                        } else {
                        ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                        <?php
                        }
                        ?>
                        <h3 class="float-text text-white"><?php echo $title; ?></h3>
                    </div>
                </a>
        <?php
            }
        } else {
            echo "<div class='error'>Category not Added.</div>";
        }
        ?>
        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

<!-- products list Section Starts Here --> 
<section class="product-menu">
    <div class="container">
        <h2 class="text-center">Products List</h2>

        <?php
        $sql2 = "SELECT * FROM tbl_shoes WHERE active='Yes' AND featured='Yes' LIMIT 6";
        $res2 = mysqli_query($conn, $sql2);

        $count2 = mysqli_num_rows($res2); // corrected variable name

        if ($count2 > 0) {
            while ($row = mysqli_fetch_assoc($res2)) {
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $description = $row['description'];
                $image_name = $row['image_name'];
        ?>
                <div class="product-menu-box">
                    <div class="product-menu-img">
                        <?php
                        if ($image_name == "") {
                            echo "<div class='error'>Image not available.</div>";
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
            echo "<div class='error'>Products not available.</div>";
        }
        ?>

        <div class="clearfix"></div>

    </div>

    <p class="text-center">
        <a href="products.php">See All Products</a>
    </p>
</section>
<!-- Product list Section Ends Here -->

<?php include('partials_front/footer.php') ?>
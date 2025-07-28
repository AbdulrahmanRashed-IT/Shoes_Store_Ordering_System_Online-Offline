<?php include('partials_front/menu.php'); ?>

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

<!-- products list Section Starts Here -->
<section class="product-menu">
    <h2 class="text-center">Products List</h2>
    <div class="container">
        <!-- <h2 class="text-center">Products List</h2> -->

        <?php
        $sql2 = "SELECT * FROM tbl_shoes WHERE active='Yes'";
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
</section>
<!-- Product list Section Ends Here -->

<?php include('partials_front/footer.php'); ?>
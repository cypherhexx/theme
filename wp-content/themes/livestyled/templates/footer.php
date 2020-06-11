<footer class="footer theme--primary flexbox justify-content-space-between align-items-center">
    <p>&copy; <?php echo get_bloginfo()." ".date('Y'); ?></p>
    <?php
        wp_nav_menu([
            'menu' => 'Footer Links',
            'menu_class' => "footer-links",
            'container' => ''
        ]);
    ?>
</footer>